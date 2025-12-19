#!/bin/bash

# ===================================================================
# Monitor Geocoding Process
# ===================================================================

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Check if process is running
if [ ! -f "geocoding.pid" ]; then
    echo "❌ No geocoding process found (geocoding.pid not found)"
    exit 1
fi

PID=$(cat geocoding.pid)

# Check if PID is still running
if ! ps -p $PID > /dev/null 2>&1; then
    echo "❌ Process $PID is not running"
    rm -f geocoding.pid
    exit 1
fi

echo "📊 Geocoding Process Monitor"
echo "============================"
echo "Process ID: $PID"
echo "Status: ✅ Running"
echo ""

# Show latest log files
echo "📄 Available log files:"
ls -la geocoding_*.log 2>/dev/null | tail -5

echo ""

# Show progress from progress file
if [ -f "geocoding_progress.json" ]; then
    echo "📈 Latest Progress:"
    echo "-------------------"
    python3 -c "
import json
try:
    with open('geocoding_progress.json', 'r') as f:
        progress = json.load(f)
    print(f\"Offset: {progress.get('offset', 0)}\")
    print(f\"Processed: {progress.get('processed_count', 0)}\")
    print(f\"Updated: {progress.get('updated_count', 0)}\")
    print(f\"Failed: {progress.get('failed_count', 0)}\")
    
    if progress.get('processed_count', 0) > 0:
        success_rate = (progress.get('updated_count', 0) / progress.get('processed_count', 1)) * 100
        print(f\"Success Rate: {success_rate:.1f}%\")
    
    import time
    if 'timestamp' in progress:
        elapsed = time.time() - progress['timestamp']
        print(f\"Last Update: {elapsed:.0f} seconds ago\")
except:
    print('Progress file not readable or not found')
"
else
    echo "⚠️ Progress file not found"
fi

echo ""

# Show recent log tail
LATEST_LOG=$(ls -t geocoding_*.log 2>/dev/null | head -1)
if [ ! -z "$LATEST_LOG" ]; then
    echo "📋 Recent Log Output (last 10 lines):"
    echo "--------------------------------------"
    tail -10 "$LATEST_LOG"
    echo ""
    echo "💡 Full log monitoring: tail -f $LATEST_LOG"
else
    echo "⚠️ No log files found"
fi

echo ""
echo "🔧 Management Commands:"
echo "  ./monitor.sh      - Show this status"
echo "  ./stop.sh         - Stop the process"
echo "  tail -f *.log     - Follow log output"