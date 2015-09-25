#define GL_PC_LINUX
#define CONFIG_FILE_SIZE = 335
#define HEADER_SIZE = 20
#define CMD_PROCESS_MSG_TYPE = 11
#include <gl_App.h>
#include <stdio.h>

INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="commandSenderData";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
	
	INT32					i;
	INT32					n;
	
	gl_TSatrefExtMsg		satrefMsg;
	
	pid_t 					pid;
	
	gl_TError 				Error;
	gl_TDevice 				ErrDev[RTIS_MAX_NO_OF_ERROR_DEV];
	gl_TDevice 				Dev;

	char 					output[200];

	/*
	*********************************************************************
	Initialize the alarm system
	*********************************************************************
	*/
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

	/*
	*********************************************************************
	Making header
	*********************************************************************
	*/
	INT16_BIT16(&satrefMsg.HeaderData.sRefId,sRefId);
	satrefMsg.HeaderData.ucMsgClass = ucMsgClass;
	satrefMsg.HeaderData.ucMsgType = CMD_PROCESS_MSG_TYPE;
	INT32_BIT32(&satrefMsg.HeaderData.lGpsSec,time(NULL));
	INT16_BIT16(&satrefMsg.HeaderData.sGpsmSec,0);
	INT16_BIT16(&satrefMsg.HeaderData.sLen,0);

	//Creating the socket
	gl_Socket_Server_Connect(gl_TDevice *Socket, INT32 iSocketType, char  *szHost, char *szService, INT32 iProtocol, INT32 iFormat, INT32 iConnectTimeout, INT32 iBlocked, gl_TError *Error);

	// Send a Satref message
	gl_Satref_WriteMsg(&tcpSocket, &satrefMsg, &Error);

	//READ FROM UDP
	iStatus = gl_Satref_ReadMsg(&udpSocket, &satrefMsg, &Error);

	//Waiting for an acknowledgement of the command
	while(i<TIMEOUT){
		if(satrefMsg.HeaderData.sLen == 0){
			gl_Sleep(1,0);
			iStatus = gl_Satref_ReadMsg(&Socket, &satrefMsg, &Error);
			i++;
		} else {			
			//TODO: read message
			return 0;
		}
	}
	printf("Timeout");
	return 1;	
}
