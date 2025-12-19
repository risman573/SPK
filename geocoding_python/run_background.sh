#!/bin/bash

# ===================================================================
# Background Processing Script dengan Real-time Monitoring
# ===================================================================

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Default values
BATCH_SIZE=50
MAX_RECORDS=""
PROVIDERS="nominatim"
MODE="alamat"
LOG_FILE="geocoding_$(date +%Y%m%d_%H%M%S).log"

# Function to show help
show_help() {
    echo "Usage: $0 [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -b, --batch-size SIZE     Batch size (default: 50)"
    echo "  -m, --max-records NUM     Maximum records to process"
    echo "  -p, --providers LIST      Providers: nominatim,google,opencage"
    echo "  --mode MODE              Processing mode: alamat,praktek,both (default: alamat)"
    echo "  -g, --google-key KEY      Google Maps API key"
    echo "  -o, --opencage-key KEY    OpenCage API key"
    echo "  -l, --log-file FILE       Log file name"
    echo "  -h, --help               Show this help"
    echo ""
    echo "Examples:"
    echo "  $0                                    # Basic run (alamat mode)"
    echo "  $0 --mode praktek                    # Process praktek addresses"
    echo "  $0 --mode both -b 100                # Process both with custom batch"
    echo "  $0 -p nominatim,google -g YOUR_KEY   # Multiple providers"
}

# Parse command line arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        -b|--batch-size)
            BATCH_SIZE="$2"
            shift 2
            ;;
        -m|--max-records)
            MAX_RECORDS="$2"
            shift 2
            ;;
        -p|--providers)
            PROVIDERS="$2"
            shift 2
            ;;
        --mode)
            MODE="$2"
            shift 2
            ;;
        -g|--google-key)
            GOOGLE_API_KEY="$2"
            shift 2
            ;;
        -o|--opencage-key)
            OPENCAGE_API_KEY="$2"
            shift 2
            ;;
        -l|--log-file)
            LOG_FILE="$2"
            shift 2
            ;;
        -h|--help)
            show_help
            exit 0
            ;;
        *)
            echo "Unknown option: $1"
            show_help
            exit 1
            ;;
    esac
done

# Check if virtual environment exists
if [ ! -d "geocoding_env" ]; then
    echo "❌ Virtual environment not found. Run ./quick_setup.sh first"
    exit 1
fi

# Activate virtual environment
source geocoding_env/bin/activate

# Build command
CMD="python geocoding_processor.py --batch-size $BATCH_SIZE --providers $PROVIDERS --mode $MODE"

if [ ! -z "$MAX_RECORDS" ]; then
    CMD="$CMD --max-records $MAX_RECORDS"
fi

if [ ! -z "$GOOGLE_API_KEY" ]; then
    CMD="$CMD --google-api-key $GOOGLE_API_KEY"
fi

if [ ! -z "$OPENCAGE_API_KEY" ]; then
    CMD="$CMD --opencage-api-key $OPENCAGE_API_KEY"
fi

echo "🚀 Starting Geocoding Background Process"
echo "========================================"
echo "Command: $CMD"
echo "Log file: $LOG_FILE"
echo "Process ID will be saved to: geocoding.pid"
echo ""

# Start process in background with logging
nohup $CMD > "$LOG_FILE" 2>&1 &
PID=$!

# Save PID to file
echo $PID > geocoding.pid

echo "✅ Process started with PID: $PID"
echo ""
echo "📊 Monitoring options:"
echo "  1. Watch log file: tail -f $LOG_FILE"
echo "  2. Check progress: ./monitor.sh"
echo "  3. Stop process: ./stop.sh"
echo ""
echo "🔍 Real-time monitoring (Ctrl+C to exit monitoring, process continues):"
echo "----------------------------------------"

# Monitor the log file
tail -f "$LOG_FILE" &
TAIL_PID=$!

# Function to cleanup on script exit
cleanup() {
    kill $TAIL_PID 2>/dev/null
    echo ""
    echo "📊 Process $PID is still running in background"
    echo "📄 Check log: tail -f $LOG_FILE"
    echo "🛑 Stop with: ./stop.sh"
}

trap cleanup INT

# Wait for user to interrupt monitoring
wait $TAIL_PID