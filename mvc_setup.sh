#!/bin/bash


# Define project structure
DIRECTORIES=(
    "./public"
    "./app"
    "./app/Controllers"
    "./app/Models"
    "./app/Views"
    "./app/Core"
    "./config"
    "./routes"
    "./storage"
    "./tests"
    "./vendor"
    "./resources"
)

FILES=(
    "./public/index.php"
    "./app/Core/Router.php"
    "./app/Core/Controller.php"
    "./app/Core/Database.php"
    "./app/Controllers/HomeController.php"
    "./app/Controllers/UserController.php"
    "./app/Models/User.php"
    "./app/Views/home.php"
    "./app/Views/users.php"
    "./config/config.php"
    "./config/database.php"
    "./routes/web.php"
    "./.htaccess"
    "./.env"
    "./composer.json"
    "./README.md"
)

# Create directories
echo "Creating project structure..."
for dir in "${DIRECTORIES[@]}"; do
    mkdir -p "$dir"
done

# Create files
echo "Creating files..."
for file in "${FILES[@]}"; do
    touch "$file"
done


# Run composer dump-autoload
cd "" || exit
composer dump-autoload

echo "✅ MVC project setup complete!"
echo "Run: cd  && php -S localhost:8000 -t public"
