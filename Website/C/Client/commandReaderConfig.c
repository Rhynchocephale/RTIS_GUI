#define GL_PC_LINUX
#define CONFIG_FILE 			"/opt/rtis/config/rtis_config.cfg"
#define TIMEOUT 				20

#include <gl_App.h>
#include <rtis_App.h>
#include <stdio.h>

INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="commandReaderConfig";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
	
	INT32					n;
	INT8					ucMsgType = 20;
	INT8					ucMsgClass = 26;
	INT32					iStatus;
		
	FILE					fp;

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
	Connecting to TCP socket
	*********************************************************************
	*/
	//iTcpStatus = gl_Socket_Server_Connect(&tcpSocket, GL_DEVICE_TYPE_TCP_CLIENT_SOCKET, GUI_IP, DEFAULT_CONNECTION_PORT, GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_BLOCKED, &Error);
	strcpy(szHost, "*");  	//Use default network card. Maybe. Should be read from a config file.
	strcpy(szService, "40000");	//Should be read from a config file.
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
					
		fp=fopen(CONFIG_FILE, "w");
		if(fp == NULL)
			exit(-1);
		
		n=0;

		//TODO: FIX PROBLEM WITH LENGTH OF STRINGS

		fprintf(fp, "; **************************************\n");
		fprintf(fp, "; RTIM Configuration file\n");
		fprintf(fp, "; Version 0.0.1\n");
		fprintf(fp, "; Date updated: 2010-11-12\n");
		fprintf(fp, ";	Severity filter :\n");
		fprintf(fp, ";		0(0x00) = No error messages\n");
		fprintf(fp, ";		1(0x01) = F\n");
		fprintf(fp, ";		3(0x03) = F+E\n");
		fprintf(fp, ";		7(0x07) = F+E+W\n");
		fprintf(fp, ";	   15(0x0F) = F+E+W+I\n");
		fprintf(fp, ";	   31(0x1F) = All\n");
		fprintf(fp, ";\n");
		fprintf(fp, ";		Add 128(0x80) to the above = D\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, "[MAIN]\n");
		fprintf(fp, "SeverityFilter=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT8);
		fprintf(fp, "StationId=%d\n",			satrefMsg.ucData[n]);			n += sizeof(UINT16);
		fprintf(fp, "RxIP_Address=%s\n",		satrefMsg.ucData[n]);			n += GL_IPADDRESS_SIZE*sizeof(UINT8);
		fprintf(fp, "RxPortNo=%d\n",			satrefMsg.ucData[n]);			n += sizeof(UINT16);
		fprintf(fp, "RxSocketType=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT8);
		fprintf(fp, "RxIOTimeout=%d\n",			satrefMsg.ucData[n]);			n += sizeof(UINT16);
		fprintf(fp, "RxConnectionTimeout=%d\n",	satrefMsg.ucData[n]);			n += sizeof(UINT16);
		fprintf(fp, "RxRetryDelay=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT16);
		fprintf(fp, "StationShortName=%s\n",	satrefMsg.ucData[n]);			n += 3*sizeof(UINT8);
		fprintf(fp, "ReceiverPosition=%.3f,",	satrefMsg.ucData[n]);			n += sizeof(FLOAT64);
		fprintf(fp, "%.3f,",					satrefMsg.ucData[n]);			n += sizeof(FLOAT64);
		fprintf(fp, "%.3f\n",					satrefMsg.ucData[n]);			n += sizeof(FLOAT64);
		fprintf(fp, ";\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, "; GNSS Alarm Server Module\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, ";\n");
		fprintf(fp, "[GNSS_ALARM_SRV]\n");
		fprintf(fp, "SeverityFilter=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT8);
		fprintf(fp, "Log=%s\n",					satrefMsg.ucData[n]?"on":"off");n += sizeof(UINT8);
		fprintf(fp, "Console=%s\n",				satrefMsg.ucData[n]?"on":"off");n += sizeof(UINT8);
		fprintf(fp, "Gui=%s\n",					satrefMsg.ucData[n]?"on":"off");n += sizeof(UINT8);
		fprintf(fp, ";\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, "; GNSS Receiver Command Server Module\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, ";\n");
		fprintf(fp, "[GNSS_RXCMD_SRV]\n");
		fprintf(fp, "SeverityFilter=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT8);
		fprintf(fp, ";\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, "; GNSS RawData Server Module\n");
		fprintf(fp, "; The sample rate is in msec and must be\n");
		fprintf(fp, "; one of the following values:\n");
		fprintf(fp, "; [10, 20, 40, 50, 100, 200, 500, 1000]\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, ";\n");
		fprintf(fp, "[GNSS_RAWDATA_SRV]\n");
		fprintf(fp, "SeverityFilter=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT8);
		fprintf(fp, "SampleRate=%d\n",			satrefMsg.ucData[n]);			n += sizeof(UINT16);
		fprintf(fp, ";\n");
		fprintf(fp, ";\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, "; GNSS Ephemerides Server Module\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, ";\n");
		fprintf(fp, "[GNSS_EPH_SRV]\n");
		fprintf(fp, "SeverityFilter=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT8);
		fprintf(fp, ";\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, "; Index Client Module\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, ";\n");
		fprintf(fp, "[INDEX_CLIENT]\n");
		fprintf(fp, "SeverityFilter=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT8);
		fprintf(fp, "TxIP_Address=%s\n",		satrefMsg.ucData[n]);			n += GL_IPADDRESS_SIZE*sizeof(UINT8);
		fprintf(fp, "TxPortNo=%d\n",			satrefMsg.ucData[n]);			n += sizeof(UINT16);
		fprintf(fp, "TxSocketType=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT8);
		fprintf(fp, "TxIOTimeout=%d\n",			satrefMsg.ucData[n]);			n += sizeof(UINT16);
		fprintf(fp, "TxConnectionTimeout=%d\n",	satrefMsg.ucData[n]);			n += sizeof(UINT16);
		fprintf(fp, "TxRetryDelay=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT16);
		fprintf(fp, ";\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, "; Processing\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, ";\n");
		fprintf(fp, "[PROCESSING]\n");
		fprintf(fp, "SeverityFilter=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT8);
		fprintf(fp, "DopplerTolerance=%.1f\n",	satrefMsg.ucData[n]);			n += sizeof(FLOAT64);
		fprintf(fp, "FilterFreq=%.1f\n",		satrefMsg.ucData[n]);			n += sizeof(FLOAT64);
		fprintf(fp, ";\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, "; Output\n");
		fprintf(fp, "; **************************************\n");
		fprintf(fp, ";\n");
		fprintf(fp, "[OUTPUT]\n");
		fprintf(fp, "SeverityFilter=%d\n",		satrefMsg.ucData[n]);			n += sizeof(UINT8);
		fprintf(fp, "RootDirectory=%s\n",		satrefMsg.ucData[n]);			n += 255*sizeof(UINT8);
		fprintf(fp, "\n");
		
		fclose(fp);

		/*
		*********************************************************************
		Making header
		*********************************************************************
		*/
		INT16_BIT16(&satrefMsg.HeaderData.sRefId,sRefId);
		satrefMsg.HeaderData.ucMsgClass = ucMsgClass;
		satrefMsg.HeaderData.ucMsgType = ucMsgType;
		INT32_BIT32(&satrefMsg.HeaderData.lGpsSec,time(NULL));
		INT16_BIT16(&satrefMsg.HeaderData.sGpsmSec,0);
		INT16_BIT16(&satrefMsg.HeaderData.sLen,1);
		
		satrefMsg.ucData[0] = 0;
		
		//msg = array of UINT8
		gl_Satref_WriteMsg(&tcpSocket, &satrefMsg, &Error);
	}
}
