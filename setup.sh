#!/bin/bash

# Define the base resources directory
RESOURCES_DIR="resources"

# Define the directory structure
DIRS=(
    "$RESOURCES_DIR"
    "$RESOURCES_DIR/views"
    "$RESOURCES_DIR/views/layouts"
    "$RESOURCES_DIR/views/partials"
    "$RESOURCES_DIR/views/admin"
    "$RESOURCES_DIR/views/professor"
    "$RESOURCES_DIR/views/vacataire"
    "$RESOURCES_DIR/views/common"
    "$RESOURCES_DIR/assets"
    "$RESOURCES_DIR/assets/css"
    "$RESOURCES_DIR/assets/js"
    "$RESOURCES_DIR/assets/images"
)

# Create each directory if it doesn't exist
for dir in "${DIRS[@]}"; do
    if [ ! -d "$dir" ]; then
        mkdir -p "$dir"
        echo "Created: $dir"
    else
        echo "Already exists: $dir"
    fi
done

# Create placeholder files
touch "$RESOURCES_DIR/views/layouts/main.php"
touch "$RESOURCES_DIR/views/partials/header.php"
touch "$RESOURCES_DIR/views/partials/sidebar.php"
touch "$RESOURCES_DIR/views/partials/footer.php"

echo "Resources folder structure created successfully!"
