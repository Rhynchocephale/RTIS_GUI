#define GL_PC_LINUX
#define TIMEOUT 				20

#include <gl_App.h>
#include <rtis_App.h>
#include <stdio.h>

INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="commandReaderProc";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
	
	INT32					n;
	
	INT8					ucMsgType = 20;
	INT8					ucMsgClass = 26;
	INT32					iStatus;

	gl_TpShm 				shmProcTbl;
	
	rtis_TProcessTableInfo	procTableInfo[RTIS_MAX_NO_OF_MAIN_PROC];
	
	gl_TSatrefExtMsg		satrefMsg;
	UINT8					msg[GL_SATREF_DATA_BUFFER_SIZE];  //NOT SURE
	
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
	iStatus = gl_Shm_Open(RTIS_KEY_PROC_TABLE, &shmProcTbl, &Error);
	if(iStatus == GL_ERROR)
	{
		gl_Error_Set(szFunctionName,GL_SEVERITY_ERROR,GL_APPLICATION_ERROR,"DDS_001_02","Unable to open monitor table. Waiting..", &Error);
		return 1;
	}
	

	/*
	*********************************************************************
	Connecting to TCP socket
	*********************************************************************
	*/
	//iTcpStatus = gl_Socket_Server_Connect(&tcpSocket, GL_DEVICE_TYPE_TCP_CLIENT_SOCKET, GUI_IP, DEFAULT_CONNECTION_PORT, GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_BLOCKED, &Error);
	strcpy(szHost, "*");  	//Use default network card. Maybe. Should be read from a config file.
	strcpy(szService, "40001");	//Should be read from a config file.
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
			printf("Communication error"); years ago
			continue;
		}
		
		if(satrefMsg.HeaderData.sLen == 0){
			//TODO: write a proper error message
			printf("Reception error");
			continue;
		}
		
		
		iStatus = system("clear");
		
		memset(&procTableInfo, 0, RTIS_MAX_NO_OF_MAIN_PROC*sizeof(rtis_TProcessTableInfo));
		
		m = 0;
		for(n=0; n<RTIS_MAX_NO_OF_MAIN_PROC;n++)
		{
			iStatus = gl_Shm_Get(pProcTbl, &procTableInfo[n], n, &Error);
						
			if (iStatus == GL_ERROR){
				//TODO: send error message
				return 1;
			}
			
			if(procTableInfo[n].Pid > 0){
				INT32_BIT32(&satrefMsg.ucData[m],procTableInfo[n].Pid);			m += sizeof(INT32);
				satrefMsg.ucData[m] = procTableInfo[n].IntName;					m += GL_PROCESS_NAME_SIZE*sizeof(char);
				INT32_BIT32(&satrefMsg.ucData[m],procTableInfo[n].iStartTime);	m += sizeof(INT32);
				satrefMsg.ucData[m] = procTableInfo[n].ActionFlag;				m += sizeof(INT8);
			} else {
				break;
			}
		}
		
		INT16_BIT16(&satrefMsg.HeaderData.sRefId,sRefId);
		satrefMsg.HeaderData.ucMsgClass = ucMsgClass;
		satrefMsg.HeaderData.ucMsgType = ucMsgType;
		INT32_BIT32(&satrefMsg.HeaderData.lGpsSec,time(NULL));
		INT16_BIT16(&satrefMsg.HeaderData.sGpsmSec,0);
		INT16_BIT16(&satrefMsg.HeaderData.sLen,m);
		
		gl_Satref_WriteMsg(&udpSocket, &satrefMsg, &Error);
	}
}
