Kapaseety
=========
Require : PHP 5.3+ , MySQL , Powershell , Addin Powercli for Vmware , [MySQL Connector .NET] (http://dev.mysql.com/downloads/file.php?id=450594)

Description:
Capacity planning for Vsphere 5.0 or higher

Install : 	
--- Rename file config.php_dist to config.php and edit with yours personnals settings.

--- Rename file kapaseety.ps1_dist to kapaseety.ps1 and edit with yours personnals settings for :

	#VMWare Serveur $VIServer 
	
	#SQL Serveur $SQLServer 
	
	#SQL Database $SQLDb
	
	#SQL User $SQLUser
	
	#SQL Password $SQLPwd
	
	and schedule job , the syntax is  "powershell.exe -File [path]\kapaseety.ps1"
	
	This task  can run with a read-only permission user for Vcenter
	
	
	
[Demonstration](http://kapaseety.ipocus.net)