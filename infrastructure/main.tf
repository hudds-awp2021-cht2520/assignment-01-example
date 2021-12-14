# Configure the Azure provider
terraform {
  required_providers {
    azurerm = {
      source  = "hashicorp/azurerm"
      version = "~> 2.65"
    }
  }

  required_version = ">= 1.1.0"
}

provider "azurerm" {
  features {}
}

provider "random" {
  
}

resource "azurerm_resource_group" "rg" {
  name     = "annotareResourceGroup"
  location = "EastUs"
}

resource "azurerm_app_service_plan" "sp" {
  name                = "signitServicePlan"
  location            = azurerm_resource_group.rg.location
  resource_group_name = azurerm_resource_group.rg.name
  kind                = "Linux"
  reserved            = true

  sku {
    tier = "Standard"
    size = "S1"
  }
}

resource "random_password" "password" {
  length = 16
  special = true
  override_special = "_%@"
}

resource "azurerm_mysql_server" "db" {
  name                = "annotaredb"
  location            = azurerm_resource_group.rg.location
  resource_group_name = azurerm_resource_group.rg.name

  administrator_login          = "mysqladminun"
  administrator_login_password = random_password.password.result

  sku_name   = "B_Gen5_2"
  storage_mb = 5120
  version    = "5.7"

  auto_grow_enabled                 = true
  backup_retention_days             = 7
  geo_redundant_backup_enabled      = false
  infrastructure_encryption_enabled = false
  public_network_access_enabled     = true
  ssl_enforcement_enabled           = true
  ssl_minimal_tls_version_enforced  = "TLS1_2"
}

resource "azurerm_app_service" "as" {
  name                = "annotareAppService"
  location            = azurerm_resource_group.rg.location
  resource_group_name = azurerm_resource_group.rg.name
  app_service_plan_id = azurerm_app_service_plan.sp.id 

  site_config {
    scm_type = "LocalGit"
  }
}  

output "password" {
  description = "The MySQL DB password is:" 
  value = random_password.password.result
  sensitive = true
}

output "output_MyTag_deploymentLocalGitUrl" {
  description = "The Azure deployment repo URL is:" 
  value = azurerm_app_service.as.source_control[0].repo_url
}