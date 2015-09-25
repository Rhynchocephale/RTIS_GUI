#define GL_PC_LINUX
#define TIMEOUT 				20

#include <gl_App.h>
#include <rtis_App.h>
#include <stdio.h>

INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="commandReaderReboot";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
		
	INT8					ucMsgType = 20;
	INT8					ucMsgClass = 26;
	INT32					iStatus;

	gl_TSatrefExtMsg		satrefMsg;
		
	gl_TError 				Error;
	gl_TDevice 				ErrDev[RTIS_MAX_NO_OF_ERROR_DEV];
	gl_TDevice 				tcpSocket;
	
	char  					szHost[GL_IPADDRESS_SIZE];
	char 					szService[6];
		
	/*
	*********************************************************************
	Initialise the alarm system
	*********************************************************************
	*/
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
	strcpy(ErrDev[0].szAlarmLogDir,RTIS_SYSTEM_ALARMLOGDIR);

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
	Connecting to TCP socket
	*********************************************************************
	*/
	//iTcpStatus = gl_Socket_Server_Connect(&tcpSocket, GL_DEVICE_TYPE_TCP_CLIENT_SOCKET, GUI_IP, DEFAULT_CONNECTION_PORT, GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_BLOCKED, &Error);
	strcpy(szHost, "*");  	//Use default network card. Maybe. Should be read from a config file.
	strcpy(szService, "40003");	//Should be read from a config file.
	iTcpStatus = gl_Socket_Server_Start(&tcpSocket, GL_DEVICE_TYPE_TCP_SERVER_SOCKET, szHost, szService, GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_BLOCKED, 1, &Error);
	
	/*
	*********************************************************************
	Listening for input
	*********************************************************************
	*/
	while(1){
		
		iStatus = gl_Satref_ReadMsg(&tcpSocket, &satrefMsg, &Error);
		
		if(iStatus != GL_OK){
			//TODO: write a proper error message
			printf("Communication error");
			continue;
		}
		
		if(satrefMsg.HeaderData.sLen == 0){
			//TODO: write a proper error message
			printf("Reception error");
			continue;
		}
						
		INT16_BIT16(&satrefMsg.HeaderData.sRefId,sRefId);
		satrefMsg.HeaderData.ucMsgClass = ucMsgClass;
		satrefMsg.HeaderData.ucMsgType = ucMsgType;
		INT32_BIT32(&satrefMsg.HeaderData.lGpsSec,time(NULL));
		INT16_BIT16(&satrefMsg.HeaderData.sGpsmSec,0);
		INT16_BIT16(&satrefMsg.HeaderData.sLen,1);
		
		satrefMsg.ucData[0] = 0;
		
		gl_Satref_WriteMsg(&tcpSocket, &satrefMsg, &Error);
		
		status = system("./RTIS_KILL_ALL.sh");
	}
}
