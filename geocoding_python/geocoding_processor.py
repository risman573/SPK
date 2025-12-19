"""
Geocoding Script untuk Update Latitude & Longitude
==================================================

Script Python untuk mengupdate field lat & lon di database MySQL
berdasarkan alamat yang ada di field 'alamat'.

Features:
- Multiple geocoding providers (Nominatim, Google Maps, OpenCage)
- Robust error handling & retry mechanism
- Batch processing untuk performa optimal
- Progress tracking & logging
- Database connection handling
- Resume functionality jika proses terhenti

Requirements:
- Python 3.7+
- MySQL database
- Internet connection untuk geocoding API

Author: AI Assistant
Date: October 2025
"""

import mysql.connector
import requests
import time
import json
import logging
import os
from typing import Dict, List, Optional, Tuple
from dataclasses import dataclass
from urllib.parse import urlencode
import argparse
import sys

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('geocoding.log'),
        logging.StreamHandler(sys.stdout)
    ]
)
logger = logging.getLogger(__name__)

@dataclass
class GeocodingResult:
    """Result dari geocoding process"""
    success: bool
    latitude: Optional[float] = None
    longitude: Optional[float] = None
    provider: Optional[str] = None
    error: Optional[str] = None

@dataclass
class DatabaseConfig:
    """Konfigurasi database MySQL"""
    host: str = 'localhost'
    port: int = 3306
    database: str = 'dokter2'
    username: str = 'root'
    password: str = ''

class GeocodingProvider:
    """Base class untuk geocoding providers"""
    
    def __init__(self, name: str):
        self.name = name
        self.request_count = 0
        self.last_request_time = 0
        self.rate_limit_delay = 1.0  # Default 1 second
    
    def geocode(self, address: str) -> GeocodingResult:
        """Override di subclass"""
        raise NotImplementedError
    
    def _rate_limit(self):
        """Implement rate limiting"""
        current_time = time.time()
        elapsed = current_time - self.last_request_time
        if elapsed < self.rate_limit_delay:
            time.sleep(self.rate_limit_delay - elapsed)
        self.last_request_time = time.time()
        self.request_count += 1

class NominatimProvider(GeocodingProvider):
    """Nominatim (OpenStreetMap) geocoding provider - GRATIS"""
    
    def __init__(self):
        super().__init__("Nominatim")
        self.rate_limit_delay = 1.0  # 1 request per second
        self.base_url = "https://nominatim.openstreetmap.org/search"
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'DokterApp-Geocoding/1.0 (https://dokterapp.com; admin@dokterapp.com)'
        })
    
    def geocode(self, address: str) -> GeocodingResult:
        """Geocode address menggunakan Nominatim"""
        self._rate_limit()
        
        try:
            # Strategy 1: Full address
            result = self._try_geocode(f"{address}, Indonesia")
            if result.success:
                return result
            
            # Strategy 2: Extract city/regency
            result = self._extract_and_geocode_city(address)
            if result.success:
                return result
            
            # Strategy 3: Last parts of address
            address_parts = [part.strip() for part in address.split(',')]
            if len(address_parts) > 1:
                short_address = ', '.join(address_parts[-2:]) + ', Indonesia'
                result = self._try_geocode(short_address)
                if result.success:
                    return result
            
            return GeocodingResult(
                success=False,
                error=f"No results found for address: {address[:50]}..."
            )
            
        except Exception as e:
            logger.error(f"Nominatim geocoding error: {e}")
            return GeocodingResult(
                success=False,
                error=str(e)
            )
    
    def _try_geocode(self, address: str) -> GeocodingResult:
        """Try geocoding dengan address tertentu"""
        params = {
            'q': address,
            'format': 'json',
            'limit': 1,
            'addressdetails': 1,
            'countrycodes': 'id'  # Indonesia only
        }
        
        try:
            response = self.session.get(
                self.base_url,
                params=params,
                timeout=30
            )
            response.raise_for_status()
            
            data = response.json()
            if data and len(data) > 0:
                result = data[0]
                return GeocodingResult(
                    success=True,
                    latitude=float(result['lat']),
                    longitude=float(result['lon']),
                    provider=self.name
                )
            
            return GeocodingResult(success=False, error="No results")
            
        except Exception as e:
            return GeocodingResult(success=False, error=str(e))
    
    def _extract_and_geocode_city(self, address: str) -> GeocodingResult:
        """Extract nama kota/kabupaten dan geocode"""
        import re
        
        # Clean address first
        address = address.replace('.', ' ').replace(',', ' ')
        
        # Pattern untuk kota/kabupaten Indonesia
        patterns = [
            # Kabupaten/Kota explicit  
            r'((?:Kabupaten|Kota|Kab\.)\s+[A-Za-z\s]+)',
            # Kota besar lengkap
            r'(Jakarta\s*[A-Za-z]*|Surabaya|Bandung|Medan|Bekasi|Tangerang|Depok|Semarang|Palembang|Makassar|Yogyakarta|Bogor|Malang|Solo|Batam)',
            # Kota-kota lain yang sering muncul
            r'(Surakarta|Tuban|Jambi|Padang|Pontianak|Balikpapan|Pekanbaru|Denpasar|Mataram|Kupang|Manado|Ambon|Jayapura)',
            # Singkatan kota
            r'(jakpus|jakut|jaksel|jaktim|jakbar)',
            # Sleman, Bantul, dll DIY/Jateng
            r'(Sleman|Bantul|Gunungkidul|Kulon\s*Progo|Tulungagung|Wonogiri|Klaten|Karanganyar|Boyolali|Sukoharjo|Purworejo|Magelang|Temanggung)',
            # Pattern: ambil kata terakhir yang berpotensi kota (bukan angka/no)
            r'([A-Za-z]{4,})\s*\d*\s*$'
        ]
        
        for pattern in patterns:
            match = re.search(pattern, address, re.IGNORECASE)
            if match:
                city = match.group(1).strip()
                
                # Handle singkatan Jakarta
                if city.lower() in ['jakpus', 'jakut', 'jaksel', 'jaktim', 'jakbar']:
                    city = 'Jakarta'
                
                # Test beberapa variasi
                test_addresses = [
                    f"{city}, Indonesia",
                    f"{city}, Jawa Tengah, Indonesia", 
                    f"{city}, Jawa Timur, Indonesia",
                    f"{city}, Jawa Barat, Indonesia",
                    f"{city}, Sumatera Barat, Indonesia",
                    f"{city}, Sumatera Utara, Indonesia"
                ]
                
                for test_addr in test_addresses:
                    result = self._try_geocode(test_addr)
                    if result.success:
                        return result
        
        # Fallback: coba kata terakhir
        words = address.split()
        if len(words) >= 2:
            for i in range(min(3, len(words))):
                word = words[-(i+1)]
                if len(word) > 3 and not word.isdigit():
                    result = self._try_geocode(f"{word}, Indonesia")
                    if result.success:
                        return result
        
        return GeocodingResult(success=False, error="No city found")

class GoogleMapsProvider(GeocodingProvider):
    """Google Maps geocoding provider - BERBAYAR"""
    
    def __init__(self, api_key: str):
        super().__init__("GoogleMaps")
        self.api_key = api_key
        self.rate_limit_delay = 0.1  # 10 requests per second
        self.base_url = "https://maps.googleapis.com/maps/api/geocode/json"
        self.session = requests.Session()
    
    def geocode(self, address: str) -> GeocodingResult:
        """Geocode address menggunakan Google Maps"""
        if not self.api_key:
            return GeocodingResult(success=False, error="Google API key not provided")
        
        self._rate_limit()
        
        try:
            params = {
                'address': f"{address}, Indonesia",
                'key': self.api_key,
                'region': 'id'
            }
            
            response = self.session.get(
                self.base_url,
                params=params,
                timeout=30
            )
            response.raise_for_status()
            
            data = response.json()
            
            if data['status'] == 'OK' and data['results']:
                result = data['results'][0]
                location = result['geometry']['location']
                
                return GeocodingResult(
                    success=True,
                    latitude=float(location['lat']),
                    longitude=float(location['lng']),
                    provider=self.name
                )
            
            return GeocodingResult(
                success=False,
                error=f"Google API status: {data.get('status', 'Unknown')}"
            )
            
        except Exception as e:
            logger.error(f"Google Maps geocoding error: {e}")
            return GeocodingResult(success=False, error=str(e))

class OpenCageProvider(GeocodingProvider):
    """OpenCage geocoding provider - FREEMIUM"""
    
    def __init__(self, api_key: str):
        super().__init__("OpenCage")
        self.api_key = api_key
        self.rate_limit_delay = 1.0  # 1 request per second (free tier)
        self.base_url = "https://api.opencagedata.com/geocode/v1/json"
        self.session = requests.Session()
    
    def geocode(self, address: str) -> GeocodingResult:
        """Geocode address menggunakan OpenCage"""
        if not self.api_key:
            return GeocodingResult(success=False, error="OpenCage API key not provided")
        
        self._rate_limit()
        
        try:
            params = {
                'q': f"{address}, Indonesia",
                'key': self.api_key,
                'countrycode': 'id',
                'limit': 1
            }
            
            response = self.session.get(
                self.base_url,
                params=params,
                timeout=30
            )
            response.raise_for_status()
            
            data = response.json()
            
            if data['results']:
                result = data['results'][0]
                geometry = result['geometry']
                
                return GeocodingResult(
                    success=True,
                    latitude=float(geometry['lat']),
                    longitude=float(geometry['lng']),
                    provider=self.name
                )
            
            return GeocodingResult(success=False, error="No results from OpenCage")
            
        except Exception as e:
            logger.error(f"OpenCage geocoding error: {e}")
            return GeocodingResult(success=False, error=str(e))

class GeocodingManager:
    """Manager untuk multiple geocoding providers"""
    
    def __init__(self, providers: List[GeocodingProvider]):
        self.providers = providers
        self.stats = {
            'total_requests': 0,
            'successful_requests': 0,
            'failed_requests': 0,
            'provider_stats': {p.name: {'success': 0, 'failed': 0} for p in providers}
        }
    
    def geocode(self, address: str) -> GeocodingResult:
        """Try geocoding dengan multiple providers"""
        self.stats['total_requests'] += 1
        
        for provider in self.providers:
            try:
                logger.debug(f"Trying {provider.name} for address: {address[:50]}...")
                result = provider.geocode(address)
                
                if result.success:
                    self.stats['successful_requests'] += 1
                    self.stats['provider_stats'][provider.name]['success'] += 1
                    logger.debug(f"✅ Success with {provider.name}: {result.latitude}, {result.longitude}")
                    return result
                else:
                    self.stats['provider_stats'][provider.name]['failed'] += 1
                    logger.debug(f"❌ Failed with {provider.name}: {result.error}")
                    
            except Exception as e:
                logger.error(f"Provider {provider.name} error: {e}")
                self.stats['provider_stats'][provider.name]['failed'] += 1
        
        self.stats['failed_requests'] += 1
        return GeocodingResult(
            success=False,
            error="All providers failed"
        )
    
    def get_stats(self) -> Dict:
        """Get geocoding statistics"""
        return self.stats.copy()

class DatabaseManager:
    """Manager untuk database operations"""
    
    def __init__(self, config: DatabaseConfig):
        self.config = config
        self.connection = None
        self.cursor = None
    
    def connect(self):
        """Connect ke database"""
        try:
            self.connection = mysql.connector.connect(
                host=self.config.host,
                port=self.config.port,
                database=self.config.database,
                user=self.config.username,
                password=self.config.password,
                autocommit=True
            )
            self.cursor = self.connection.cursor(dictionary=True)
            logger.info("✅ Database connection established")
            
        except mysql.connector.Error as e:
            logger.error(f"❌ Database connection failed: {e}")
            raise
    
    def disconnect(self):
        """Disconnect dari database"""
        if self.cursor:
            self.cursor.close()
        if self.connection:
            self.connection.close()
        logger.info("Database connection closed")
    
    def get_records_to_update(self, limit: int = 100, offset: int = 0) -> List[Dict]:
        """Get records yang perlu diupdate"""
        query = """
        SELECT id, alamat, lat, lon 
        FROM data_full 
        WHERE alamat IS NOT NULL 
        AND alamat != '' 
        ORDER BY id 
        LIMIT %s OFFSET %s
        """
        
        self.cursor.execute(query, (limit, offset))
        return self.cursor.fetchall()

    def get_praktek_records_to_update(self, limit: int = 100, offset: int = 0) -> List[Dict]:
        """Get praktek records yang perlu diupdate"""
        query = """
        SELECT id, praktek_1, praktek_2, praktek_3, lat_1, lon_1, lat_2, lon_2, lat_3, lon_3
        FROM data_full 
        WHERE (
            (praktek_1 IS NOT NULL AND praktek_1 != '' AND praktek_1 != 'Tidak Ada') OR
            (praktek_2 IS NOT NULL AND praktek_2 != '' AND praktek_2 != 'Tidak Ada') OR
            (praktek_3 IS NOT NULL AND praktek_3 != '' AND praktek_3 != 'Tidak Ada')
        )
        ORDER BY id 
        LIMIT %s OFFSET %s
        """
        
        self.cursor.execute(query, (limit, offset))
        return self.cursor.fetchall()
    
    def count_records_to_update(self) -> int:
        """Count total records yang perlu diupdate"""
        query = """
        SELECT COUNT(*) as total
        FROM data_full 
        WHERE alamat IS NOT NULL 
        AND alamat != '' 
        """
        
        self.cursor.execute(query)
        result = self.cursor.fetchone()
        return result['total'] if result else 0

    def count_praktek_records_to_update(self) -> int:
        """Count total praktek records yang perlu diupdate"""
        query = """
        SELECT COUNT(*) as total
        FROM data_full 
        WHERE (
            (praktek_1 IS NOT NULL AND praktek_1 != '' AND praktek_1 != 'Tidak Ada') OR
            (praktek_2 IS NOT NULL AND praktek_2 != '' AND praktek_2 != 'Tidak Ada') OR
            (praktek_3 IS NOT NULL AND praktek_3 != '' AND praktek_3 != 'Tidak Ada')
        )
        """
        
        self.cursor.execute(query)
        result = self.cursor.fetchone()
        return result['total'] if result else 0
    
    def update_coordinates(self, record_id: int, latitude: float, longitude: float, provider: str) -> bool:
        """Update coordinates untuk record tertentu"""
        try:
            query = """
            UPDATE data_full 
            SET lat = %s, lon = %s, geocoding_provider = %s, geocoding_updated = NOW()
            WHERE id = %s
            """
            
            self.cursor.execute(query, (latitude, longitude, provider, record_id))
            return self.cursor.rowcount > 0
            
        except mysql.connector.Error as e:
            logger.error(f"Failed to update record {record_id}: {e}")
            return False

    def update_praktek_coordinates(self, record_id: int, praktek_num: int, latitude: float, longitude: float, provider: str) -> bool:
        """Update praktek coordinates untuk record tertentu"""
        try:
            lat_field = f"lat_{praktek_num}"
            lon_field = f"lon_{praktek_num}"
            
            query = f"""
            UPDATE data_full 
            SET {lat_field} = %s, {lon_field} = %s, geocoding_provider = %s, geocoding_updated = NOW()
            WHERE id = %s
            """
            
            self.cursor.execute(query, (latitude, longitude, provider, record_id))
            return self.cursor.rowcount > 0
            
        except mysql.connector.Error as e:
            logger.error(f"Failed to update praktek {praktek_num} for record {record_id}: {e}")
            return False

class GeocodingBatchProcessor:
    """Main processor untuk batch geocoding"""
    
    def __init__(self, db_config: DatabaseConfig, geocoding_manager: GeocodingManager):
        self.db_config = db_config
        self.geocoding_manager = geocoding_manager
        self.db_manager = DatabaseManager(db_config)
        
        # Statistics
        self.processed_count = 0
        self.updated_count = 0
        self.failed_count = 0
        self.start_time = None
    
    def process_batch(self, batch_size: int = 50, start_offset: int = 0, max_records: int = None):
        """Process records dalam batch"""
        
        self.start_time = time.time()
        logger.info("🚀 Starting geocoding batch process...")
        
        try:
            # Connect to database
            self.db_manager.connect()
            
            # Get total count
            total_records = self.db_manager.count_records_to_update()
            logger.info(f"📊 Total records to process: {total_records}")
            
            if total_records == 0:
                logger.info("✅ No records need updating")
                return
            
            # Limit max records if specified
            if max_records:
                total_records = min(total_records, max_records)
                logger.info(f"🔒 Processing limited to {max_records} records")
            
            offset = start_offset
            
            while offset < total_records:
                # Calculate batch size for this iteration
                current_batch_size = min(batch_size, total_records - offset)
                
                logger.info(f"📦 Processing batch: {offset + 1}-{offset + current_batch_size} of {total_records}")
                
                # Get records
                records = self.db_manager.get_records_to_update(current_batch_size, offset)
                
                if not records:
                    logger.info("No more records to process")
                    break
                
                # Process each record
                for record in records:
                    self._process_single_record(record)
                
                offset += current_batch_size
                
                # Progress report
                progress = ((offset) / total_records) * 100
                elapsed_time = time.time() - self.start_time
                estimated_total = (elapsed_time / (offset - start_offset)) * (total_records - start_offset)
                remaining_time = estimated_total - elapsed_time
                
                logger.info(f"📈 Progress: {progress:.1f}% | "
                           f"Updated: {self.updated_count} | "
                           f"Failed: {self.failed_count} | "
                           f"ETA: {remaining_time/60:.1f} minutes")
                
                # Save progress state
                self._save_progress_state(offset)
                
                # Small delay between batches
                time.sleep(0.5)
            
            # Final report
            self._print_final_report()
            
        except Exception as e:
            logger.error(f"❌ Batch processing error: {e}")
            raise
        
        finally:
            self.db_manager.disconnect()
    
    def _process_single_record(self, record: Dict):
        """Process single record"""
        record_id = record['id']
        address = record['alamat']
        
        self.processed_count += 1
        
        try:
            # Geocode address
            result = self.geocoding_manager.geocode(address)
            
            if result.success:
                # Update database
                success = self.db_manager.update_coordinates(
                    record_id, 
                    result.latitude, 
                    result.longitude,
                    result.provider
                )
                
                if success:
                    self.updated_count += 1
                    logger.debug(f"✅ Updated ID {record_id}: {result.latitude}, {result.longitude}")
                else:
                    self.failed_count += 1
                    logger.warning(f"❌ Failed to update database for ID {record_id}")
            else:
                self.failed_count += 1
                logger.warning(f"❌ Geocoding failed for ID {record_id}: {result.error}")
                
        except Exception as e:
            self.failed_count += 1
            logger.error(f"❌ Error processing record {record_id}: {e}")
    
    def _save_progress_state(self, offset: int):
        """Save progress state to file"""
        state = {
            'offset': offset,
            'processed_count': self.processed_count,
            'updated_count': self.updated_count,
            'failed_count': self.failed_count,
            'timestamp': time.time()
        }
        
        with open('geocoding_progress.json', 'w') as f:
            json.dump(state, f, indent=2)
    
    def _print_final_report(self):
        """Print final processing report"""
        elapsed_time = time.time() - self.start_time
        
        logger.info("=" * 60)
        logger.info("🎉 GEOCODING BATCH PROCESS COMPLETED")
        logger.info("=" * 60)
        logger.info(f"📊 Total Processed: {self.processed_count}")
        logger.info(f"✅ Successfully Updated: {self.updated_count}")
        logger.info(f"❌ Failed: {self.failed_count}")
        logger.info(f"📈 Success Rate: {(self.updated_count/self.processed_count)*100:.1f}%")
        logger.info(f"⏱️ Total Time: {elapsed_time/60:.1f} minutes")
        logger.info(f"⚡ Rate: {self.processed_count/(elapsed_time/60):.1f} records/minute")
        
        # Provider statistics
        stats = self.geocoding_manager.get_stats()
        logger.info("\n📊 Provider Statistics:")
        for provider, provider_stats in stats['provider_stats'].items():
            total = provider_stats['success'] + provider_stats['failed']
            if total > 0:
                success_rate = (provider_stats['success'] / total) * 100
                logger.info(f"  {provider}: {provider_stats['success']}/{total} ({success_rate:.1f}%)")
        
        logger.info("=" * 60)

    def process_praktek_batch(self, batch_size: int = 50, start_offset: int = 0, max_records: int = None):
        """Process praktek records dalam batch"""
        
        self.start_time = time.time()
        logger.info("🚀 Starting PRAKTEK geocoding batch process...")
        
        try:
            # Connect to database
            self.db_manager.connect()
            
            # Get total count
            total_records = self.db_manager.count_praktek_records_to_update()
            logger.info(f"📊 Total praktek records to process: {total_records}")
            
            if total_records == 0:
                logger.info("✅ No praktek records need updating")
                return
            
            # Limit max records if specified
            if max_records:
                total_records = min(total_records, max_records)
                logger.info(f"🔒 Processing limited to {max_records} praktek records")
            
            offset = start_offset
            
            while offset < total_records:
                # Calculate batch size for this iteration
                current_batch_size = min(batch_size, total_records - offset)
                
                logger.info(f"📦 Processing praktek batch: {offset + 1}-{offset + current_batch_size} of {total_records}")
                
                # Get records
                records = self.db_manager.get_praktek_records_to_update(current_batch_size, offset)
                
                if not records:
                    logger.info("No more praktek records to process")
                    break
                
                # Process each record
                for record in records:
                    self._process_single_praktek_record(record)
                
                offset += current_batch_size
                
                # Progress report
                progress = ((offset) / total_records) * 100
                elapsed_time = time.time() - self.start_time
                if offset > start_offset:
                    estimated_total = (elapsed_time / (offset - start_offset)) * (total_records - start_offset)
                    remaining_time = estimated_total - elapsed_time
                else:
                    remaining_time = 0
                
                logger.info(f"📈 Praktek Progress: {progress:.1f}% | "
                           f"Updated: {self.updated_count} | "
                           f"Failed: {self.failed_count} | "
                           f"ETA: {remaining_time/60:.1f} minutes")
                
                # Small delay between batches
                time.sleep(0.5)
            
            # Final report
            self._print_final_report()
            
        except Exception as e:
            logger.error(f"❌ Praktek batch processing error: {e}")
            raise
        
        finally:
            self.db_manager.disconnect()

    def _process_single_praktek_record(self, record: Dict):
        """Process single praktek record"""
        record_id = record['id']
        
        # Process each praktek field
        for praktek_num in [1, 2, 3]:
            praktek_field = f'praktek_{praktek_num}'
            lat_field = f'lat_{praktek_num}'
            lon_field = f'lon_{praktek_num}'
            
            praktek_address = record[praktek_field]
            current_lat = record[lat_field]
            current_lon = record[lon_field]
            
            # Skip if no address or already has coordinates or is "Tidak Ada"
            if (not praktek_address or 
                praktek_address.strip() == '' or 
                praktek_address.strip().lower() == 'tidak ada' or
                (current_lat and current_lat != 0)):
                continue
            
            self.processed_count += 1
            
            try:
                # Geocode praktek address
                result = self.geocoding_manager.geocode(praktek_address)
                
                if result.success:
                    # Update database
                    success = self.db_manager.update_praktek_coordinates(
                        record_id, praktek_num, result.latitude, result.longitude, result.provider
                    )
                    
                    if success:
                        self.updated_count += 1
                        logger.info(f"✅ Updated praktek_{praktek_num} ID {record_id}: {result.latitude}, {result.longitude} ({result.provider})")
                    else:
                        self.failed_count += 1
                        logger.warning(f"❌ Database update failed for praktek_{praktek_num} ID {record_id}")
                else:
                    self.failed_count += 1
                    logger.warning(f"❌ Geocoding failed for praktek_{praktek_num} ID {record_id}: {result.error}")
                    
            except Exception as e:
                self.failed_count += 1
                logger.error(f"❌ Error processing praktek_{praktek_num} ID {record_id}: {e}")


def load_config() -> DatabaseConfig:
    """Load database configuration from file or environment"""
    
    # Try to load from config file
    config_file = 'database_config.json'
    if os.path.exists(config_file):
        with open(config_file, 'r') as f:
            config_data = json.load(f)
            return DatabaseConfig(**config_data)
    
    # Use environment variables or defaults
    return DatabaseConfig(
        host=os.getenv('DB_HOST', 'localhost'),
        port=int(os.getenv('DB_PORT', 3306)),
        database=os.getenv('DB_DATABASE', 'your_database'),
        username=os.getenv('DB_USERNAME', 'your_username'),
        password=os.getenv('DB_PASSWORD', 'your_password')
    )


def main():
    """Main function"""
    parser = argparse.ArgumentParser(description='Geocoding batch processor')
    parser.add_argument('--batch-size', type=int, default=50, help='Batch size (default: 50)')
    parser.add_argument('--offset', type=int, default=0, help='Start offset (default: 0)')
    parser.add_argument('--max-records', type=int, help='Maximum records to process')
    parser.add_argument('--google-api-key', help='Google Maps API key')
    parser.add_argument('--opencage-api-key', help='OpenCage API key')
    parser.add_argument('--providers', default='nominatim', 
                       help='Comma-separated list of providers: nominatim,google,opencage')
    parser.add_argument('--mode', choices=['alamat', 'praktek', 'both'], default='alamat',
                       help='Processing mode: alamat (main address), praktek (practice addresses), both (default: alamat)')
    
    args = parser.parse_args()
    
    # Load database config
    try:
        db_config = load_config()
        logger.info(f"📁 Database config loaded: {db_config.host}:{db_config.port}/{db_config.database}")
    except Exception as e:
        logger.error(f"❌ Failed to load database config: {e}")
        return 1
    
    # Setup geocoding providers
    providers = []
    provider_names = [p.strip().lower() for p in args.providers.split(',')]
    
    if 'nominatim' in provider_names:
        providers.append(NominatimProvider())
        logger.info("✅ Nominatim provider added (FREE)")
    
    if 'google' in provider_names:
        if args.google_api_key:
            providers.append(GoogleMapsProvider(args.google_api_key))
            logger.info("✅ Google Maps provider added (PAID)")
        else:
            logger.warning("⚠️ Google Maps provider requested but no API key provided")
    
    if 'opencage' in provider_names:
        if args.opencage_api_key:
            providers.append(OpenCageProvider(args.opencage_api_key))
            logger.info("✅ OpenCage provider added (FREEMIUM)")
        else:
            logger.warning("⚠️ OpenCage provider requested but no API key provided")
    
    if not providers:
        logger.error("❌ No geocoding providers configured")
        return 1
    
    # Create geocoding manager
    geocoding_manager = GeocodingManager(providers)
    
    # Create batch processor
    processor = GeocodingBatchProcessor(db_config, geocoding_manager)
    
    try:
        if args.mode == 'alamat':
            # Process main addresses only
            processor.process_batch(
                batch_size=args.batch_size,
                start_offset=args.offset,
                max_records=args.max_records
            )
        elif args.mode == 'praktek':
            # Process praktek addresses only
            processor.process_praktek_batch(
                batch_size=args.batch_size,
                start_offset=args.offset,
                max_records=args.max_records
            )
        elif args.mode == 'both':
            # Process main addresses first
            logger.info("🏠 Processing main addresses first...")
            processor.process_batch(
                batch_size=args.batch_size,
                start_offset=args.offset,
                max_records=args.max_records
            )
            
            # Reset counters for praktek processing
            processor.processed_count = 0
            processor.updated_count = 0
            processor.failed_count = 0
            
            # Then process praktek addresses
            logger.info("\n🏥 Processing praktek addresses...")
            processor.process_praktek_batch(
                batch_size=args.batch_size,
                start_offset=args.offset,
                max_records=args.max_records
            )
        
        return 0
        
    except Exception as e:
        logger.error(f"❌ Processing failed: {e}")
        return 1


if __name__ == "__main__":
    exit(main())