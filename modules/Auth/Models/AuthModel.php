<?php
class AuthModel extends Model {
    public function kullaniciGetir($kadiVeyaEposta) {
        $stmt = $this->db->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = :kadi OR eposta = :eposta LIMIT 1");
        $stmt->execute(['kadi' => $kadiVeyaEposta, 'eposta' => $kadiVeyaEposta]);
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res;
    }
}
