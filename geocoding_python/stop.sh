#!/bin/bash

# ===================================================================
# Stop Geocoding Process
# ===================================================================

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

if [ ! -f "geocoding.pid" ]; then
    echo "❌ No geocoding process found (geocoding.pid not found)"
    exit 1
fi

PID=$(cat geocoding.pid)

# Check if PID is still running
if ! ps -p $PID > /dev/null 2>&1; then
    echo "⚠️ Process $PID is not running"
    rm -f geocoding.pid
    exit 1
fi

echo "🛑 Stopping geocoding process $PID..."

# Try graceful shutdown first
kill -TERM $PID

# Wait a bit
sleep 3

# Check if still running
if ps -p $PID > /dev/null 2>&1; then
    echo "⚠️ Process still running, forcing shutdown..."
    kill -KILL $PID
    sleep 1
fi

# Final check
if ps -p $PID > /dev/null 2>&1; then
    echo "❌ Failed to stop process $PID"
    exit 1
else
    echo "✅ Process $PID stopped successfully"
    rm -f geocoding.pid
fi

# Show final progress if available
if [ -f "geocoding_progress.json" ]; then
    echo ""
    echo "📊 Final Progress:"
    python3 -c "
import json
try:
    with open('geocoding_progress.json', 'r') as f:
        progress = json.load(f)
    print(f\"Processed: {progress.get('processed_count', 0)}\")
    print(f\"Updated: {progress.get('updated_count', 0)}\")
    print(f\"Failed: {progress.get('failed_count', 0)}\")
    
    if progress.get('processed_count', 0) > 0:
        success_rate = (progress.get('updated_count', 0) / progress.get('processed_count', 1)) * 100
        print(f\"Success Rate: {success_rate:.1f}%\")
except:
    print('Could not read progress')
"
fi

echo ""
echo "💡 To resume processing from where it stopped:"
echo "   ./run_background.sh --offset LAST_OFFSET"