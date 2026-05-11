# Requires: npm install -g uglify-js
# Exec from project root: .\dev-tools\core-bundle.ps1
# Set project root path here:
$rootpath = "D:\dev\twoquakers\peanut2\peanut-core"
$corePath = "$rootpath\web.root\tq-peanut"
Set-Location -Path "$corePath\pnut\core"
uglifyjs PeanutLoader.js `
-o peanut-loader.min.js  -c -m

uglifyjs `
App.js `
KnockoutHelper.js `
WaitMessage.js `
Services.js `
ViewModelBase.js `
-o peanut-core.min.js  -c -m
Set-Location -Path $rootpath