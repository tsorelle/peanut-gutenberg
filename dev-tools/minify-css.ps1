# requires: npm install -g clean-css-cli
# Exec from project root: .\dev-tools\minify-css.ps1
# Set project root path here:
$rootpath = "D:\dev\twoquakers\peanut2\peanut-core"
$themePath = "$rootpath\web.root\application\themes\default"
Set-Location -Path "$themePath"
Write-Host "Current dir: $(Get-Location)"
cleancss -o extra.min.css extra.css
Set-Location -Path $rootpath