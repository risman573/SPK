"""
Auto-detect CodeIgniter database configuration
"""

import os
import re
import json

def detect_codeigniter_db_config():
    """Detect database config from CodeIgniter app"""
    
    # Look for Database.php in CodeIgniter structure
    possible_paths = [
        '../app/Config/Database.php',
        '../../app/Config/Database.php',
        '../Config/Database.php'
    ]
    
    for path in possible_paths:
        if os.path.exists(path):
            try:
                config = parse_ci_database_config(path)
                if config:
                    return config
            except Exception as e:
                print(f"Error parsing {path}: {e}")
    
    return None

def parse_ci_database_config(file_path):
    """Parse CodeIgniter Database.php config file"""
    
    try:
        with open(file_path, 'r') as f:
            content = f.read()
        
        # Extract default database configuration
        config = {}
        
        # Find hostname
        hostname_match = re.search(r"'hostname'\s*=>\s*['\"]([^'\"]+)['\"]", content)
        if hostname_match:
            config['host'] = hostname_match.group(1)
        
        # Find database name
        database_match = re.search(r"'database'\s*=>\s*['\"]([^'\"]+)['\"]", content)
        if database_match:
            config['database'] = database_match.group(1)
        
        # Find username
        username_match = re.search(r"'username'\s*=>\s*['\"]([^'\"]+)['\"]", content)
        if username_match:
            config['username'] = username_match.group(1)
        
        # Find password
        password_match = re.search(r"'password'\s*=>\s*['\"]([^'\"]*)['\"]", content)
        if password_match:
            config['password'] = password_match.group(1)
        
        # Find port (optional)
        port_match = re.search(r"'port'\s*=>\s*(\d+)", content)
        if port_match:
            config['port'] = int(port_match.group(1))
        else:
            config['port'] = 3306
        
        # Validate required fields
        required_fields = ['host', 'database', 'username']
        if all(field in config for field in required_fields):
            return config
        else:
            print(f"Missing required database config fields: {required_fields}")
            return None
            
    except Exception as e:
        print(f"Error reading database config: {e}")
        return None

def create_database_config():
    """Create database_config.json file"""
    
    print("🔍 Auto-detecting CodeIgniter database configuration...")
    
    # Try to detect from CodeIgniter
    config = detect_codeigniter_db_config()
    
    if config:
        print("✅ CodeIgniter database configuration detected!")
        print(f"   Host: {config['host']}")
        print(f"   Database: {config['database']}")
        print(f"   Username: {config['username']}")
        print(f"   Port: {config['port']}")
    else:
        print("⚠️ Could not detect CodeIgniter configuration")
        print("Creating template configuration...")
        
        config = {
            "host": "localhost",
            "port": 3306,
            "database": "your_database_name",
            "username": "your_username",
            "password": "your_password"
        }
    
    # Save to file
    with open('database_config.json', 'w') as f:
        json.dump(config, f, indent=2)
    
    print("💾 Configuration saved to database_config.json")
    
    if not detect_codeigniter_db_config():
        print("\n⚠️ Please edit database_config.json with your actual database credentials")
    
    return config

if __name__ == "__main__":
    create_database_config()