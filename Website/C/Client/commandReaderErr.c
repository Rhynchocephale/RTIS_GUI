#define GL_PC_LINUX
#define ERROR_FETCH_TIME_LENGTH 300

#include <gl_App.h>
#include <rtis_App.h>
#include <stdio.h>

INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="commandReaderErr";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
	
	INT32					n;
	
	INT8					fetchError;
	INT8					ucMsgType = 20;
	INT8					ucMsgClass = 26;
	INT32					iStatus;
	
	char 					errorData[GL_RB_ERROR_STR_SIZE];
		
	gl_TSatrefExtMsg		satrefMsg;
	
	gl_TError 				Error;
	gl_TDevice 				ErrDev[RTIS_MAX_NO_OF_ERROR_DEV];
	gl_TDevice 				tcpSocket;
	gl_TDevice 				udpSocket;
	
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
	Connecting to the different tables
	*********************************************************************
	*/
	iStatus = gl_RBuf_Open(RTIS_KEY_RB_ALARM_SRV, &pRBuf, &Error);
	if(iStatus == GL_ERROR)
	{
		gl_Error_Set(szFunctionName,GL_SEVERITY_ERROR,GL_APPLICATION_ERROR,"DDS_001_02","Unable to open error ringbuffer. Waiting..", &Error);
		return 1;
	}

	/*
	*********************************************************************
	Connecting to TCP socket
	*********************************************************************
	*/
	//iTcpStatus = gl_Socket_Server_Connect(&tcpSocket, GL_DEVICE_TYPE_TCP_CLIENT_SOCKET, GUI_IP, DEFAULT_CONNECTION_PORT, GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_BLOCKED, &Error);
	strcpy(szHost, "*");  	//Use default network card. Maybe. Should be read from a config file.
	strcpy(szService, "40004");	//Should be read from a config file.
	iTcpStatus = gl_Socket_Server_Start(&tcpSocket, GL_DEVICE_TYPE_TCP_SERVER_SOCKET, szHost,  szService, GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_BLOCKED, 1, &Error);
	/*
	*********************************************************************
	Connecting to UDP socket
	*********************************************************************
	*/
	//iUdpStatus = gl_Socket_Server_Connect(&udpSocket, GL_DEVICE_TYPE_UDP_CLIENT_SOCKET, GUI_IP, DEFAULT_CONNECTION_PORT+1, GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_BLOCKED, &Error);
	iUdpStatus = gl_Socket_Server_Start(&udpSocket, GL_DEVICE_TYPE_UDP_SERVER_SOCKET, szHost,  szService2, GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_BLOCKED, 1, &Error);

	
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
		
		cmdReceived = satrefMsg.HeaderData.lGpsSec;
		
		//while less than ERROR_FETCH_TIME_LENGTH seconds have elapsed since reception of the message
		while(time(NULL) - cmdReceived < ERROR_FETCH_TIME_LENGTH) {
			// Read
			iStatus = system("clear");
			
			iStatus = gl_RBuf_Get(&pRBuf, &errorData, &Error);
			if (iStatus == GL_ERROR){
				//TODO: send error message
				return 1;
			}
			
			INT16_BIT16(&satrefMsg.HeaderData.sRefId,sRefId);
			satrefMsg.HeaderData.ucMsgClass = ucMsgClass;
			satrefMsg.HeaderData.ucMsgType = ucMsgType;
			INT32_BIT32(&satrefMsg.HeaderData.lGpsSec,time(NULL));
			INT16_BIT16(&satrefMsg.HeaderData.sGpsmSec,0);
			INT16_BIT16(&satrefMsg.HeaderData.sLen,GL_RB_ERROR_STR_SIZE);
			
			satrefMsg.ucData[0] = errorData;
			
			gl_Satref_WriteMsg(&udpSocket, &satrefMsg, &Error);
		}
		//will now restart the loop and wait for new messages.
	}
}
