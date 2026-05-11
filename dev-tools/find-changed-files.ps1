# Exec from project root:
# .\dev-tools\find-changed-files.ps1 -SubDirectory "web.root/tq-peanut/pnut/packages/peanut-content" -AfterDate "2026-04-01 09:00"
# or enter parameters at prompts:
# .\dev-tools\find-changed-files.ps1
param (
    [Parameter(Mandatory = $true)]
    [string]$SubDirectory,

    [Parameter(Mandatory = $true)]
    [datetime]$AfterDate
)

# Set project root path here:
$rootpath = "D:\dev\twoquakers\peanut2\peanut-core"

Set-Location -Path "$rootpath\$SubDirectory"

Get-ChildItem -Recurse -File |
        Where-Object { $_.LastWriteTime -gt $AfterDate } |
        Sort-Object LastWriteTime -Descending |
        Select-Object LastWriteTime, FullName

Set-Location -Path $rootpath