"""
Simple script untuk test database connection dan melihat data
"""

import json
import mysql.connector
from typing import Dict, List

def load_db_config() -> Dict:
    """Load database configuration"""
    try:
        with open('database_config.json', 'r') as f:
            return json.load(f)
    except FileNotFoundError:
        print("❌ database_config.json not found")
        return None
    except json.JSONDecodeError:
        print("❌ Invalid JSON in database_config.json")
        return None

def test_database_connection():
    """Test database connection dan tampilkan sample data"""
    
    config = load_db_config()
    if not config:
        return False
    
    try:
        # Connect to database
        print("🔌 Connecting to database...")
        connection = mysql.connector.connect(
            host=config['host'],
            port=config['port'],
            database=config['database'],
            user=config['username'],
            password=config['password']
        )
        
        cursor = connection.cursor(dictionary=True)
        print("✅ Database connection successful!")
        
        # Check table exists
        cursor.execute("SHOW TABLES LIKE 'data_full'")
        table_exists = cursor.fetchone()
        
        if not table_exists:
            print("❌ Table 'data_full' not found")
            return False
        
        print("✅ Table 'data_full' found")
        
        # Check table structure
        cursor.execute("DESCRIBE data_full")
        columns = cursor.fetchall()
        
        print("\n📋 Table Structure:")
        required_columns = ['id', 'alamat', 'lat', 'lon']
        for col in required_columns:
            found = any(c['Field'] == col for c in columns)
            status = "✅" if found else "❌"
            print(f"  {status} {col}")
        
        # Count total records
        cursor.execute("SELECT COUNT(*) as total FROM data_full")
        total_count = cursor.fetchone()['total']
        print(f"\n📊 Total records: {total_count}")
        
        # Count records with addresses
        cursor.execute("""
            SELECT COUNT(*) as total 
            FROM data_full 
            WHERE alamat IS NOT NULL AND alamat != ''
        """)
        address_count = cursor.fetchone()['total']
        print(f"📍 Records with addresses: {address_count}")
        
        # Count records needing geocoding
        cursor.execute("""
            SELECT COUNT(*) as total 
            FROM data_full 
            WHERE alamat IS NOT NULL 
            AND alamat != '' 
            AND (lat IS NULL OR lon IS NULL OR lat = 0 OR lon = 0)
        """)
        need_geocoding = cursor.fetchone()['total']
        print(f"🎯 Records needing geocoding: {need_geocoding}")
        
        # Show sample data
        cursor.execute("""
            SELECT id, alamat, lat, lon 
            FROM data_full 
            WHERE alamat IS NOT NULL AND alamat != ''
            LIMIT 5
        """)
        samples = cursor.fetchall()
        
        print(f"\n📝 Sample data:")
        for sample in samples:
            status = "✅" if sample['lat'] and sample['lon'] else "❌"
            print(f"  {status} ID {sample['id']}: {sample['alamat'][:60]}...")
            if sample['lat'] and sample['lon']:
                print(f"      Coordinates: {sample['lat']}, {sample['lon']}")
        
        # Check if geocoding_provider column exists
        cursor.execute("SHOW COLUMNS FROM data_full LIKE 'geocoding_provider'")
        provider_col = cursor.fetchone()
        
        if not provider_col:
            print("\n⚠️ Column 'geocoding_provider' not found. Adding it...")
            cursor.execute("""
                ALTER TABLE data_full 
                ADD COLUMN geocoding_provider VARCHAR(50) NULL,
                ADD COLUMN geocoding_updated TIMESTAMP NULL
            """)
            connection.commit()
            print("✅ Added geocoding_provider and geocoding_updated columns")
        else:
            print("\n✅ geocoding_provider column exists")
        
        cursor.close()
        connection.close()
        
        print(f"\n🎯 Ready to process {need_geocoding} records!")
        return True
        
    except mysql.connector.Error as e:
        print(f"❌ Database error: {e}")
        return False
    except Exception as e:
        print(f"❌ Unexpected error: {e}")
        return False

if __name__ == "__main__":
    print("🔍 Database Connection Test")
    print("=" * 50)
    
    success = test_database_connection()
    
    if success:
        print("\n✅ Database test passed! Ready to run geocoding.")
        print("\nNext steps:")
        print("1. Run test: python test_geocoding.py")
        print("2. Run batch: python geocoding_processor.py --batch-size 10")
    else:
        print("\n❌ Database test failed. Please check configuration.")
        print("\nSteps to fix:")
        print("1. Edit database_config.json with correct credentials")
        print("2. Ensure MySQL server is running")
        print("3. Verify database and table exist")