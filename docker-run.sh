#!/bin/bash
set -e

echo "🚀 Starting Symfony Application with PostgreSQL..."

# Function to check if Docker is running
check_docker() {
    if ! docker info > /dev/null 2>&1; then
        echo "❌ Docker is not running. Please start Docker and try again."
        exit 1
    fi
}

# Function to build and start containers
start_containers() {
    echo "📦 Building and starting containers..."
    
    # Copy docker environment file
    cp .env.docker .env
    
    # Build and start containers
    docker-compose up --build -d
    
    echo "⏳ Waiting for containers to be ready..."
    sleep 10
    
    # Check if containers are running
    if docker-compose ps | grep -q "Up"; then
        echo "✅ Containers are running successfully!"
        
        # Show container status
        echo "📊 Container status:"
        docker-compose ps
        
        # Show logs
        echo "📋 Recent logs:"
        docker-compose logs --tail=20
        
        echo ""
        echo "🌐 Application is available at: http://localhost"
        echo "🗄️  PostgreSQL is available at: localhost:5432"
        echo ""
        echo "🔧 Useful commands:"
        echo "  - View logs: docker-compose logs -f"
        echo "  - Stop containers: docker-compose down"
        echo "  - Restart containers: docker-compose restart"
        echo "  - Access app container: docker-compose exec app sh"
        echo "  - Access database: docker-compose exec database psql -U app -d app"
        
    else
        echo "❌ Some containers failed to start. Check logs with: docker-compose logs"
        exit 1
    fi
}

# Function to stop containers
stop_containers() {
    echo "🛑 Stopping containers..."
    docker-compose down
    echo "✅ Containers stopped successfully!"
}

# Function to reset everything
reset_containers() {
    echo "🔄 Resetting containers and volumes..."
    docker-compose down -v
    docker-compose build --no-cache
    docker-compose up -d
    echo "✅ Containers reset successfully!"
}

# Main script
case "${1:-start}" in
    start)
        check_docker
        start_containers
        ;;
    stop)
        stop_containers
        ;;
    restart)
        stop_containers
        sleep 2
        start_containers
        ;;
    reset)
        check_docker
        reset_containers
        ;;
    logs)
        docker-compose logs -f
        ;;
    status)
        docker-compose ps
        ;;
    *)
        echo "Usage: $0 {start|stop|restart|reset|logs|status}"
        echo ""
        echo "Commands:"
        echo "  start   - Build and start containers (default)"
        echo "  stop    - Stop containers"
        echo "  restart - Restart containers"
        echo "  reset   - Reset containers and volumes"
        echo "  logs    - Show container logs"
        echo "  status  - Show container status"
        exit 1
        ;;
esac