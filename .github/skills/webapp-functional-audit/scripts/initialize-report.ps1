param(
    [Parameter(Mandatory = $true)]
    [string]$RunFolder
)

$assetsPath = Join-Path $PSScriptRoot "..\assets"
$reportTemplate = Join-Path $assetsPath "report-template.md"
$dbTemplate = Join-Path $assetsPath "database-checks-template.md"

New-Item -ItemType Directory -Path $RunFolder -Force | Out-Null
New-Item -ItemType Directory -Path (Join-Path $RunFolder "screenshots") -Force | Out-Null
New-Item -ItemType Directory -Path (Join-Path $RunFolder "artifacts") -Force | Out-Null

Copy-Item -Path $reportTemplate -Destination (Join-Path $RunFolder "report.md") -Force
Copy-Item -Path $dbTemplate -Destination (Join-Path $RunFolder "database-checks.md") -Force

Write-Output "Initialized report folder: $RunFolder"
