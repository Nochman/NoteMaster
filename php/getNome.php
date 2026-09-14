<?php
session_start();
echo $_SESSION["user_nome"] ?? "ospite";