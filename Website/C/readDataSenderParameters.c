#define _GNU_SOURCE
#define PATH_TO_CONFIG_FILE = "/tmp/dataSender.conf"
#include <gl_App.h>
#include <rtis_App.h>
#include <stdio.h>

struct dataSenderConf readDataSenderParameters()
{
	char 					szApplicationName[]="RTIS";
	char 					szProcessName[]="dataSender";
	char 					szFunctionName[]="readDataSenderParameters";
	
	UINT8 					i;
	char 					line[64];
	char 					*paramName;
	char 					*charParamValue;
	UINT8 					uParamValue;
	const char 				s[2] = ":";
	
	dataSenderConf confContent;
	
	FILE *file = fopen (PATH_TO_CONFIG_FILE, "r");
	
	if (file != NULL)
	{
		i = 0;
		while (i < 4){ 
			if(fgets(line, sizeof line, file) != NULL){ /* read a line */
				i++;

				paramName  = strtok(str, s);
				paramValue = strtok(str, s);
				if(isdigit(charParamValue)) {
					uParamValue = charParamValue - '0'; //char to int
				} else {
					break;
					//ERROR INVALID VALUE
				}
				
				switch(paramName){
					case "Flow" :
					   confContent.uFlow = uParamValue;
					   break; 
					case "Proc" :
					   confContent.uProc = uParamValue;
					   break;
					case "Mon" :
					   confContent.uMon = uParamValue;
					   break;
					case "Err" :
					   confContent.uErr = uParamValue;
					   break;
				
			} else {
				break;
				//ERROR INCOMPLETE FILE
			}
		}
		fclose (file);
	}
	else {
		//ERROR OPENING FILE
	}
	return confContent;
}
