#define GL_PC_LINUX
#define CONFIG_FILE_SIZE = 335
#define HEADER_SIZE = 20
#define CMD_CONFIG_MSG_TYPE = 11
#include <gl_App.h>
#include <stdio.h>

INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="commandSenderReboot";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
	
	INT32					i;
	INT32					n;	
	
	gl_TSatrefExtMsg		satrefMsg;
	gl_TSatrefExtHeader		msgHead;
	UINT8					msgBody[CONFIG_FILE_SIZE];
	
	pid_t 					pid;
	
	gl_TError 				Error;
	gl_TDevice 				ErrDev[RTIS_MAX_NO_OF_ERROR_DEV];
	
	const char *			delimitor=",";
		
	/*
	*********************************************************************
	Initialize the alarm system
	*********************************************************************
	*/
	iReboot = GL_OFF;
	iData = GL_OFF;

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
	if(argc != 1){
		printf("Expecting 1 argument, %i received.",argc-1);
		fflush(stdout);
		return(GL_ERROR);
	}

	n=0;

	/*
	*********************************************************************
	Making header
	*********************************************************************
	*/
	INT16_BIT16(&msgHead[n],sRefId);  n +=sizeof(INT16);
	msgHead[n] = ucMsgClass; n +=sizeof(UINT8);
	msgHead[n] = CMD_CONFIG_MSG_TYPE; n +=sizeof(UINT8);
	INT32_BIT32(&msgHead[n],lGpsSec); n +=sizeof(INT32);
	INT16_BIT16(&msgHead[n],sGpsmSec); n +=sizeof(INT16);
	INT16_BIT16(&msgHead[n],sLen); n +=sizeof(INT16);
	
	gl_Satref_PackMsg(gl_TSatrefExtMsg *SatrefExtMsg, UINT8 *ucBuf, INT32 *iLen,  gl_TError *Error);

	// Write or send a Satref message
	gl_Satref_WriteMsg(gl_TDevice *Dev, gl_TSatrefExtMsg *SatrefExtMsg, gl_TError *Error);
	
	return 0;
}

