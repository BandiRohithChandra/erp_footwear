<?php
// install_offline.php
file_put_contents(__DIR__ . '/storage/offline_installed', 'installed');
echo "Offline admin installation complete!";
