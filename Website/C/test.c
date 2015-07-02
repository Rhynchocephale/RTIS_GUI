#define GL_PC_LINUX
#define MAX_SIZE_OF_CONFIG_FILE = 335
#define CMD_CONFIG_MSG_TYPE = 11
#include <gl_App.h>
#include <stdio.h>

INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="commandSender";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
	
	INT32					i;
	INT32					n;
	
	INT32 					iProcess;
	INT32 					iGpsEph;
	INT32 					iGloEph;
	INT32 					iRBuf;
	INT32 					iMonitor;
	INT32 					iConfig;
	
	INT8					msgBody[MAX_SIZE_OF_CONFIG_FILE];
	
	pid_t 					pid;
	
	gl_TError 				Error;
	gl_TDevice 				ErrDev[RTIS_MAX_NO_OF_ERROR_DEV];
	
	const char *			delimitor=",";
	
	/*
	*********************************************************************
	Initialize the alarm system
	*********************************************************************
	*/
	iProcess = GL_OFF;
	iGpsEph = GL_OFF;
	iGloEph = GL_OFF;
	iRBuf = GL_OFF;
	iMonitor = GL_OFF;
	iConfig = GL_OFF;

	pid = getpid();

	gl_Device_Clear(RTIS_MAX_NO_OF_ERROR_DEV,&ErrDev[0]);
	gl_Error_Clear(&Error);

	gl_Error_SetAppName(szApplicationName,&Error);
	gl_Error_SetProcessName(szProcessName,&Error);
	/*
	*********************************************************************
	Set the error device(s).
	Up to SDDS_MAX_NO_OF_ERROR_DEV can be defined.
	*********************************************************************
	*/
	ErrDev[0].iType = GL_DEVICE_TYPE_CONSOLE;
	ErrDev[0].iProtocol = GL_DEVICE_PROTOCOL_NONE;
	ErrDev[0].iFormat = GL_DEVICE_FORMAT_TEXT;
	ErrDev[0].iHandle = -1;

	gl_Error_SetDevice(1, &ErrDev[0], &Error);
	/*
	*********************************************************************
	Set the severity filter, that is, event level written to the error
	devices
	*********************************************************************
	*/
	Error.ucSeverityFilter = GL_PRINT_SEVERITY_ALL;
	
	/*
	*********************************************************************
	Checking number of arguments
	*********************************************************************
	*/
	if(argc != 29){
		printf("Expecting 28 arguments, %i received.",argc);
		fflush(stdout);
		return(GL_ERROR);
	}

	char *token;
	n=0;

	INT16_BIT16(&msgBody[n],sRefId);  n +=sizeof(INT16);
	msgBody[n] = ucMsgClass; n +=sizeof(UINT8);
	msgBody[n] = CMD_CONFIG_MSG_TYPE; n +=sizeof(UINT8);
	INT32_BIT32(&msgBody[n],lGpsSec); n +=sizeof(INT32);
	INT16_BIT16(&msgBody[n],sGpsmSec); n +=sizeof(INT16);
	INT16_BIT16(&msgBody[n],sLen); n +=sizeof(INT16);
	
	fclose(fp);
	
	return 0;
}

