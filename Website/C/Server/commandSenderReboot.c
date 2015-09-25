#define GL_PC_LINUX

#include <gl_App.h>
#include <stdio.h>

INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="commandSenderReboot";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
	
	pid_t					pid;
	
	INT32					i;
	
	gl_TSatrefExtMsg		satrefMsg;
	
	gl_TError 				Error;
	gl_TDevice 				ErrDev[RTIS_MAX_NO_OF_ERROR_DEV];
			
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
		printf("Expecting no argument, %i received.",argc-1);
		fflush(stdout);
		return(GL_ERROR);
	}

	iTcpStatus = gl_Socket_Server_Connect(&tcpSocket, GL_DEVICE_TYPE_TCP_CLIENT_SOCKET, , , GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_BLOCKED, &Error);

	INT16_BIT16(&satrefMsg.HeaderData.sRefId,sRefId);
	satrefMsg.HeaderData.ucMsgClass = ucMsgClass;
	satrefMsg.HeaderData.ucMsgType = ucMsgType;
	INT32_BIT32(&satrefMsg.HeaderData.lGpsSec,time(NULL));
	INT16_BIT16(&satrefMsg.HeaderData.sGpsmSec,0);
	INT16_BIT16(&satrefMsg.HeaderData.sLen,1);
	
	satrefMsg.ucData[0] = 0;
	
	gl_Satref_WriteMsg(&tcpSocket, &satrefMsg, &Error);
	
	iStatus = gl_Satref_ReadMsg(&tcpSocket, &satrefMsg, &Error);

	//Waiting for an acknowledgement of the command
	while(i<TIMEOUT){
		if(satrefMsg.HeaderData.sLen == 0){
			gl_Sleep(1,0);
			iStatus = gl_Satref_ReadMsg(&tcpSocket, &satrefMsg, &Error);
			i++;
		} else {			
			//TODO: read message
			return 0;
		}
	}
	printf("Timeout");
	return 1;	
	
	return 0;
}

