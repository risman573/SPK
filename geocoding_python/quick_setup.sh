#!/bin/bash

# ===================================================================
# Quick Setup untuk Geocoding Python Script
# ===================================================================

echo "🚀 Quick Setup Geocoding Python Script"
echo "======================================"

# Check if we're in the right directory
if [ ! -f "geocoding_processor.py" ]; then
    echo "❌ Error: Please run this from the geocoding_python directory"
    exit 1
fi

# Step 1: Setup virtual environment jika belum ada
if [ ! -d "geocoding_env" ]; then
    echo "📦 Creating Python virtual environment..."
    python3 -m venv geocoding_env
    
    if [ $? -ne 0 ]; then
        echo "❌ Failed to create virtual environment"
        exit 1
    fi
fi

# Step 2: Activate virtual environment
echo "🔧 Activating virtual environment..."
source geocoding_env/bin/activate

# Step 3: Install requirements
echo "📚 Installing Python packages..."
pip install --upgrade pip
pip install -r requirements.txt

# Step 4: Auto-detect database configuration
echo "🔍 Setting up database configuration..."
python setup_config.py

# Step 5: Test database connection
echo "🔌 Testing database connection..."
python test_database.py

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Setup completed successfully!"
    echo ""
    echo "🎯 Next steps:"
    echo "1. Test geocoding: python test_geocoding.py"
    echo "2. Run small batch: python geocoding_processor.py --max-records 10"
    echo "3. Run full batch: python geocoding_processor.py"
    echo ""
    echo "💡 Remember to activate virtual environment:"
    echo "   source geocoding_env/bin/activate"
else
    echo ""
    echo "⚠️ Database connection failed"
    echo "Please edit database_config.json with correct credentials"
    echo "Then run: python test_database.py"
fi