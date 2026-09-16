<?php
Router::ekle("GET", "giris", [AuthController::class, "girisForm"]);
Router::ekle("POST", "giris-yap", [AuthController::class, "girisYap"]);
Router::ekle("GET", "cikis", [AuthController::class, "cikis"]);
