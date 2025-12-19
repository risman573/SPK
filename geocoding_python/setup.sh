#!/bin/bash

# ===================================================================
# Geocoding Python Script - Installation & Setup
# ===================================================================

echo "🚀 Setting up Python Geocoding Environment..."

# Check Python version
python3 --version
if [ $? -ne 0 ]; then
    echo "❌ Python 3 not found. Please install Python 3.7 or higher"
    exit 1
fi

# Create virtual environment
echo "📦 Creating virtual environment..."
python3 -m venv geocoding_env

# Activate virtual environment
echo "🔧 Activating virtual environment..."
source geocoding_env/bin/activate

# Upgrade pip
echo "⬆️ Upgrading pip..."
pip install --upgrade pip

# Install requirements
echo "📚 Installing requirements..."
pip install -r requirements.txt

echo "✅ Setup completed!"
echo ""
echo "📋 Next steps:"
echo "1. Edit database_config.json with your database credentials"
echo "2. Run the script with: python geocoding_processor.py"
echo ""
echo "🔧 Usage examples:"
echo "  # Basic usage (50 records per batch)"
echo "  python geocoding_processor.py"
echo ""
echo "  # Custom batch size"
echo "  python geocoding_processor.py --batch-size 100"
echo ""
echo "  # Start from specific offset"
echo "  python geocoding_processor.py --offset 1000"
echo ""
echo "  # Limit total records"
echo "  python geocoding_processor.py --max-records 500"
echo ""
echo "  # Use multiple providers"
echo "  python geocoding_processor.py --providers nominatim,google --google-api-key YOUR_KEY"
echo ""
echo "💡 Always activate the virtual environment first:"
echo "  source geocoding_env/bin/activate"