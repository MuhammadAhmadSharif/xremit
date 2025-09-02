#!/bin/bash

echo "🚀 XRemit Pro - Render Deployment Script"
echo "========================================"

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: This script must be run from the Laravel project root directory"
    exit 1
fi

echo "✅ Laravel project detected"

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Error: Docker is not installed. Please install Docker first."
    exit 1
fi

echo "✅ Docker is installed"

# Check if required files exist
required_files=("Dockerfile" "docker/nginx.conf" "docker/supervisord.conf" ".dockerignore")
missing_files=()

for file in "${required_files[@]}"; do
    if [ ! -f "$file" ]; then
        missing_files+=("$file")
    fi
done

if [ ${#missing_files[@]} -ne 0 ]; then
    echo "❌ Error: Missing required files:"
    for file in "${missing_files[@]}"; do
        echo "   - $file"
    done
    exit 1
fi

echo "✅ All required files are present"

# Test Docker build
echo "🔨 Testing Docker build..."
if docker build -t xremit-web-test . > /dev/null 2>&1; then
    echo "✅ Docker build test successful"
    docker rmi xremit-web-test > /dev/null 2>&1
else
    echo "❌ Error: Docker build test failed"
    echo "Please check your Dockerfile and try again"
    exit 1
fi

# Check git status
if [ -d ".git" ]; then
    echo "📝 Git repository detected"
    
    # Check if there are uncommitted changes
    if [ -n "$(git status --porcelain)" ]; then
        echo "⚠️  Warning: You have uncommitted changes"
        echo "   Consider committing them before deployment:"
        echo "   git add ."
        echo "   git commit -m 'Prepare for deployment'"
        echo "   git push origin main"
    else
        echo "✅ No uncommitted changes"
    fi
else
    echo "⚠️  Warning: No Git repository detected"
    echo "   Consider initializing Git for version control"
fi

echo ""
echo "🎯 Next Steps:"
echo "1. Commit and push your code to Git (if not done already)"
echo "2. Go to https://dashboard.render.com/"
echo "3. Create a new Web Service"
echo "4. Connect your Git repository"
echo "5. Use these settings:"
echo "   - Environment: Docker"
echo "   - Build Command: docker build -t xremit-web ."
echo "   - Start Command: docker run -p \$PORT:80 xremit-web"
echo ""
echo "📚 See DEPLOYMENT_GUIDE.md for detailed instructions"
echo ""
echo "✅ Deployment preparation complete!"
