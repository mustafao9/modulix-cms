
// Otonom WAF Kalkanını Devreye Al
require_once __DIR__ . '/Mail.php';
require_once __DIR__ . '/WAF.php';
WAF::calistir();

// Self-Healing Çekirdek Onarım Motoru
require_once __DIR__ . '/SelfHealer.php';
SelfHealer::otomatikanalizVeOnar();
