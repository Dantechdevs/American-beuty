<?php
$file = 'resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

// Remove the misplaced function and extra closing brace
$old = '            return false;
        async function handleWhatsapp(e) {
            e.preventDefault();
            const phone = document.getElementById(\'whatsapp-phone\').value.trim();
            try {
                const res = await fetch(\'/subscribers/whatsapp\', {
                    method: \'POST\',
                    headers: {
                        \'Content-Type\': \'application/json\',
                        \'X-CSRF-TOKEN\': document.querySelector(\'meta[name=csrf-token]\').content
                    },
                    body: JSON.stringify({ phone })
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById(\'whatsapp-form\').reset();
                    window.open(\'https://wa.me/254722794265?text=Hi!%20I%20just%20subscribed%20for%20updates.\', \'_blank\');
                } else {
                    alert(data.message || \'Something went wrong.\');
                }
            } catch(err) {
                alert(\'Error. Please try again.\');
            }
        }
        }';

$new = '            return false;
        }
        async function handleWhatsapp(e) {
            e.preventDefault();
            const phone = document.getElementById(\'whatsapp-phone\').value.trim();
            if (!phone) return;
            try {
                const res = await fetch(\'/subscribers/whatsapp\', {
                    method: \'POST\',
                    headers: {
                        \'Content-Type\': \'application/json\',
                        \'X-CSRF-TOKEN\': document.querySelector(\'meta[name=csrf-token]\').content
                    },
                    body: JSON.stringify({ phone })
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById(\'whatsapp-form\').reset();
                    window.open(\'https://wa.me/254722794265?text=Hi!%20I%20just%20subscribed%20for%20updates.\', \'_blank\');
                } else {
                    alert(data.message || \'Something went wrong.\');
                }
            } catch(err) {
                alert(\'Error. Please try again.\');
            }
        }';

if (strpos($content, $old) !== false) {
    file_put_contents($file, str_replace($old, $new, $content));
    echo "SUCCESS\n";
} else {
    echo "ERROR - string not found\n";
}
