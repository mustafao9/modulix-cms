<?php
Router::ekle("GET", "install", [InstallController::class, "index"]);
Router::ekle("GET", "install/step2", [InstallController::class, "step2"]);
Router::ekle("POST", "install/kur", [InstallController::class, "kur"]);
