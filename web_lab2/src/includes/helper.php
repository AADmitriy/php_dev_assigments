<?php
function includeWithParams(string $filePath, array $params = []): void {
    if (file_exists($filePath)) {
        extract($params); 
        include $filePath; 
    }
}