"""
Test script untuk geocoding providers
Gunakan untuk test sebelum running batch processing
"""

import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from geocoding_processor import (
    NominatimProvider, 
    GoogleMapsProvider, 
    OpenCageProvider,
    GeocodingManager
)
import time

def test_providers():
    """Test semua providers dengan sample address"""
    
    # Sample addresses for testing
    test_addresses = [
        "Jl. Pandeansari Blok II No.1, RT009/ RW063, Gandok, Condongcatur, Depok, Sleman, Yogyakarta",
        "Jl. Sudirman No. 1, Jakarta Pusat",
        "Jl. Malioboro, Yogyakarta",
        "Jl. Diponeogoro 123, Surabaya",
        "Alamat tidak valid 12345"
    ]
    
    # Setup providers
    providers = [NominatimProvider()]
    
    # Add Google if API key available
    google_api_key = os.getenv('GOOGLE_API_KEY')
    if google_api_key:
        providers.append(GoogleMapsProvider(google_api_key))
        print("✅ Google Maps provider added")
    else:
        print("⚠️ Google API key not found (set GOOGLE_API_KEY env var)")
    
    # Add OpenCage if API key available
    opencage_api_key = os.getenv('OPENCAGE_API_KEY')
    if opencage_api_key:
        providers.append(OpenCageProvider(opencage_api_key))
        print("✅ OpenCage provider added")
    else:
        print("⚠️ OpenCage API key not found (set OPENCAGE_API_KEY env var)")
    
    manager = GeocodingManager(providers)
    
    print(f"\n🧪 Testing {len(providers)} provider(s) with {len(test_addresses)} addresses...")
    print("=" * 80)
    
    for i, address in enumerate(test_addresses, 1):
        print(f"\n{i}. Testing: {address}")
        print("-" * 60)
        
        start_time = time.time()
        result = manager.geocode(address)
        end_time = time.time()
        
        if result.success:
            print(f"✅ SUCCESS ({result.provider})")
            print(f"   Latitude: {result.latitude}")
            print(f"   Longitude: {result.longitude}")
            print(f"   Time: {(end_time - start_time):.2f}s")
        else:
            print(f"❌ FAILED: {result.error}")
            print(f"   Time: {(end_time - start_time):.2f}s")
    
    # Print final statistics
    print("\n" + "=" * 80)
    print("📊 FINAL STATISTICS")
    print("=" * 80)
    stats = manager.get_stats()
    print(f"Total Requests: {stats['total_requests']}")
    print(f"Successful: {stats['successful_requests']}")
    print(f"Failed: {stats['failed_requests']}")
    print(f"Success Rate: {(stats['successful_requests']/stats['total_requests'])*100:.1f}%")
    
    print("\nProvider Statistics:")
    for provider, provider_stats in stats['provider_stats'].items():
        total = provider_stats['success'] + provider_stats['failed']
        if total > 0:
            success_rate = (provider_stats['success'] / total) * 100
            print(f"  {provider}: {provider_stats['success']}/{total} ({success_rate:.1f}%)")

if __name__ == "__main__":
    test_providers()