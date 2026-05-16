# Deployment script for peanut-block
# This script copies the build output to the peanut plugin's blocks directory

$projectRoot="D:\dev\twoquakers\peanut2\peanut-gutenberg"

$source = Join-Path $PSScriptRoot "build\peanut-block"
$destination = Join-Path $projectRoot "\web.root\wp-content\plugins\peanut\blocks\peanut-block"
Write-Host "Deploying peanut-block..."
Write-Host "Source: $source"
Write-Host "Destination: $destination"

if (!(Test-Path $source)) {
    Write-Error "Source directory not found: $source"
    return
}

if (!(Test-Path $destination)) {
    Write-Error "Target directory not found: $source"
    return
}

# Copy files and overwrite existing
Copy-Item -Path "$source\*" -Destination $destination -Recurse -Force

Write-Host "Deployment complete."
