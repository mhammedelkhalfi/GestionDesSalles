resource "docker_network" "php_network" {
  name = "new_php_network"
  lifecycle {
    ignore_changes = [name]
  }
}

# Image PHP avec Apache
resource "docker_image" "php_apache" {
  name = "php:7.4-apache"
}

# Conteneur PHP avec Apache
resource "docker_container" "php_web" {
  name  = "php-web-container"
  image = docker_image.php_apache.name
  ports {
    internal = 80
    external = 8080
  }
  volumes {
    host_path      = "C:/xampp/htdocs/GestionDesSalles/app"
    container_path = "/var/www/html"
  }
  networks_advanced {
    name = docker_network.php_network.name
  }
   command = [
    "bash",
    "-c",
    "apt-get update && apt-get install -y libmariadb-dev && docker-php-ext-install pdo pdo_mysql && apache2-foreground"
  ]
}


# Image MySQL
resource "docker_image" "mysql_image" {
  name = "mysql:5.7"
}

# Conteneur MySQL
resource "docker_container" "mysql_db" {
  name  = "mysql-container"
  image = docker_image.mysql_image.name
  ports {
    internal = 3306
    external = 3306
  }
  env = [
    "MYSQL_ROOT_PASSWORD=root",          # Mot de passe pour l'utilisateur root
    "MYSQL_DATABASE=reservation_db",     # Nom de la base de données
    "MYSQL_PASSWORD=root"                # Peut être supprimé si non nécessaire
  ]
  networks_advanced {
    name = docker_network.php_network.name
  }
}


# Image phpMyAdmin
resource "docker_image" "phpmyadmin_image" {
  name = "phpmyadmin/phpmyadmin"
}

# Conteneur phpMyAdmin
resource "docker_container" "phpmyadmin" {
  name  = "phpmyadmin-container"
  image = docker_image.phpmyadmin_image.name
  ports {
    internal = 80
    external = 8081
  }
  env = [
    "PMA_HOST=mysql-container",  # Nom du conteneur MySQL
    "PMA_PORT=3306",
    "PMA_USER=root",
    "PMA_PASSWORD=root"
  ]
  networks_advanced {
    name = docker_network.php_network.name
  }
  depends_on = [docker_container.mysql_db]  # Dépendance explicite
}



# Image Grafana
resource "docker_image" "grafana_image" {
  name = "grafana/grafana"
}

# Conteneur Grafana
resource "docker_container" "grafana" {
  name  = "grafana-container"
  image = docker_image.grafana_image.name
  ports {
    internal = 3000
    external = 3000
  }
  env = [
    "GF_SECURITY_ADMIN_PASSWORD=admin"  # Définir un mot de passe administrateur pour Grafana
  ]
  networks_advanced {
    name = docker_network.php_network.name
  }
  depends_on = [docker_container.php_web, docker_container.mysql_db]  # Dépendances explicites
}