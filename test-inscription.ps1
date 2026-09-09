# Test workflow inscription

Write-Host "=== 1. Vérification de la liste des inscrits ===" -ForegroundColor Cyan
Invoke-RestMethod -Uri "http://localhost:8081/list.php" -Method GET

Write-Host "`n=== 2. Ajout d'un nouvel inscrit ===" -ForegroundColor Cyan
$body = "nom=TestUser&email=testuser@example.com"
Invoke-RestMethod -Uri "http://localhost:8081/api.php" -Method POST -Body $body -ContentType "application/x-www-form-urlencoded"

Write-Host "`n=== 3. Vérification après inscription ===" -ForegroundColor Cyan
Invoke-RestMethod -Uri "http://localhost:8081/list.php" -Method GET
