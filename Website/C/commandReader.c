#define GL_PC_LINUX
#define ERROR_FETCH_TIME_LENGTH 300
#define CONFIG_FILE 			"config.conf"
#define TIMEOUT 				20
#define DEFAULT_CONNECTION_PORT 20000
#define FIRST_MSG_TYPE			20

#include <gl_App.h>
#include <rtis_App.h>
#include <stdio.h>


//Accepts two argument: first is the number of the station, next is 0 for processes or 1 for monitor table
INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="commandReader";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
	
	INT32					n;
	INT8					hasForked=0;
	INT8					fetchError;
	INT8					ucMsgType;
	INT8					ucMsgClass = 26;
	INT32					iStatus;
	INT32					fd[2];
	
	FILE					fp;

	gl_TpShm 				pProcTbl;
	gl_TpShm 				pMonTbl;
	gl_TpShm 				pInterfaceTbl;
	gl_TpShm 				monTableInfo;
	gl_TpShm 				procTableInfo;
	gl_TpShm 				interfaceTableInfo;

	gl_TSatrefExtMsg		satrefMsg;
	UINT8					ucBuf[GL_SATREF_DATA_BUFFER_SIZE];  //NOT SURE
	
	pid_t					myPid;
	
	gl_TError 				Error;
	gl_TDevice 				ErrDev[RTIS_MAX_NO_OF_ERROR_DEV];
	gl_TDevice 				Socket;
	
	GL_DLL_CALL_TYPE_INT32	serverHandle;
	
	const char *			delimitor=",";
		
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
	strcpy(ErrDev[0].szAlarmLogDir,"./"); //CHANGE THAT PATH

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
	iStatus = gl_Shm_Open(RTIS_KEY_PROC_TABLE, &pProcTbl, &Error);
	while (iStatus == GL_ERROR)
	{
		gl_Error_Set(szFunctionName,GL_SEVERITY_ERROR,GL_APPLICATION_ERROR,"DDS_001_02","Unable to open process table. Waiting...", &Error);
		gl_Sleep(5,0);
		iStatus = gl_Shm_Open(RTIS_KEY_PROC_TABLE, &pProcTbl, &Error);
	}
	
	iStatus = gl_Shm_Open(RTIS_KEY_MON_TABLE, &pMonTbl, &Error);
	while (iStatus == GL_ERROR)
	{
		gl_Error_Set(szFunctionName,GL_SEVERITY_ERROR,GL_APPLICATION_ERROR,"DDS_001_02","Unable to open monitor table. Waiting..", &Error);
		gl_Sleep(5,0);
		iStatus = gl_Shm_Open(RTIS_KEY_MON_TABLE, &pMonTbl, &Error);
	}
	
	iStatus = gl_Shm_Open(RTIS_KEY_INTERFACE_TABLE, &pInterfaceTbl, &Error);
	while (iStatus == GL_ERROR)
	{
		gl_Error_Set(szFunctionName,GL_SEVERITY_ERROR,GL_APPLICATION_ERROR,"DDS_001_02","Unable to open interface table. Waiting ...", &Error);
		gl_Sleep(1,0);
		iStatus = gl_Shm_Open(RTIS_KEY_INTERFACE_TABLE, &pInterfaceTbl, &Error);
	}


	/*
	*********************************************************************
	Connecting to socket
	*********************************************************************
	*/
	iStatus = gl_Socket_Server_Connect(&Socket, GL_DEVICE_TYPE_TCP_CLIENT_SOCKET, ipAdressIWantToConnectTo, DEFAULT_CONNECTION_PORT, GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_BLOCKED, &Error);

	/*
	*********************************************************************
	Listening for input
	*********************************************************************
	*/
	while(1){
		//TODO: CLEAR SATREFMSG
		iStatus = gl_Satref_ReadMsg(&Socket, &satrefMsg, &Error);
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
		
		ucMsgType = satrefMsg.HeaderData.ucMsgType;

		/*
		*********************************************************************
		Configuration file
		*********************************************************************
		*/
		if(ucMsgType == FIRST_MSG_TYPE){
			
			fp=fopen(CONFIG_FILE, "w");
			if(fp == NULL)
				exit(-1);
			
			n=0;

			PROBLEM WITH LENGTH OF STRINGS

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
			fprintf(fp, "RxIP_Address=%s\n",		satrefMsg.ucData[n]);			n += 15*sizeof(UINT8);
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
			fprintf(fp, "TxIP_Address=%s\n",		satrefMsg.ucData[n]);			n += 15*sizeof(UINT8);
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
			Sending confirmation message
			*********************************************************************
			*/
			//TODO: CLEAR SATREFMSG
			/*
			*********************************************************************
			Making header
			*********************************************************************
			*/
			INT16_BIT16(&satrefMsg.HeaderData.sRefId,sRefId);
			satrefMsg.HeaderData.ucMsgClass = ucMsgClass;
			satrefMsg.HeaderData.ucMsgType = ucMsgType+6;
			INT32_BIT32(&satrefMsg.HeaderData.lGpsSec,time(NULL));
			INT16_BIT16(&satrefMsg.HeaderData.sGpsmSec,0);
			INT16_BIT16(&satrefMsg.HeaderData.sLen,0);
					
		/*
		*********************************************************************
		Processes
		*********************************************************************
		*/
		} else if(ucMsgType == FIRST_MSG_TYPE+1){
			
			iStatus = system("clear");
			for(n=0; n<RTIS_MAX_NO_OF_MAIN_PROC;n++)
			{
				iStatus = gl_Shm_Get(pProcTbl, &procTableInfo, n, &Error);
				if (iStatus == GL_ERROR)
					goto Proc_Init; //TODO

				if (tableInfo.Pid > 0)
					rtis_PrintTableRow(n, RTIS_PROC_TABLE, &tableInfo, "");  //TODO: WHAT IS THIS FUNCTION? REPLACE THAT WITH MESSAGE
			}
			iStatus = system("clear");
		
		/*
		*********************************************************************
		Monitor
		*********************************************************************
		*/	
		} else if(ucMsgType == FIRST_MSG_TYPE+2){

			iStatus = system("clear");
			iStatus = gl_Shm_Get(pMonTbl, &monTableInfo, GL_SHM_SINGLE, &Error);
			if (iStatus == GL_ERROR)
				goto Monitor_Init; //TODO

			iStatus = gl_Shm_Get(pInterfaceTbl, &interfaceTableInfo, GL_SHM_SINGLE, &Error);
			if (iStatus == GL_ERROR)
				goto Monitor_Init; //TODO

			iStatus = system("clear");


			//TODO: CLEAR SATREFMSG
			/*
			*********************************************************************
			Making header
			*********************************************************************
			*/
			INT16_BIT16(&satrefMsg.HeaderData.sRefId,sRefId);
			satrefMsg.HeaderData.ucMsgClass = ucMsgClass;
			satrefMsg.HeaderData.ucMsgType = CMD_CONFIG_MSG_TYPE;
			INT32_BIT32(&satrefMsg.HeaderData.lGpsSec,time(NULL));
			INT16_BIT16(&satrefMsg.HeaderData.sGpsmSec,0);
			INT16_BIT16(&satrefMsg.HeaderData.sLen,2*sizeof(INT16)+4*sizeof(INT32)+4*sizeof(MYSTERY)); n +=sizeof(INT16);
			
			/*
			*********************************************************************
			Making body
			*********************************************************************
			*/
			n=0;
			satrefMsg.ucData[n] = interfaceTableInfo.szIPAddress; n += //MYSTERY;
			INT16_BIT16(&satrefMsg.ucData[n],interfaceTableInfo.iPortNo);  n +=sizeof(INT16);
			satrefMsg.ucData[n] = interfaceTableInfo.szCmdInterfaceModule; n += //MYSTERY;
			satrefMsg.ucData[n] = interfaceTableInfo.szRawDataInterfaceModule; n += //MYSTERY;
			satrefMsg.ucData[n] = interfaceTableInfo.szEphDataInterfaceModule; n += //MYSTERY;
			INT16_BIT16(&satrefMsg.ucData[n],interfaceTableInfo.iSampleRate);  n +=sizeof(INT16);
			INT32_BIT32(&satrefMsg.ucData[n],monTableInfo.iNoOfRawMeas);  n +=sizeof(INT32);
			INT32_BIT32(&satrefMsg.ucData[n],monTableInfo.iNoOfLostRawMeas);  n +=sizeof(INT32);
			INT32_BIT32(&satrefMsg.ucData[n],monTableInfo.iNoOfIQMeas);  n +=sizeof(INT32);
			INT32_BIT32(&satrefMsg.ucData[n],monTableInfo.iNoOfLostIQMeas);  n +=sizeof(INT32);
			INT32_BIT32(&satrefMsg.ucData[n],monTableInfo.iOther);  n +=sizeof(INT32);
			
			monitorMsg = gl_Satref_PackMsg(gl_TSatrefExtMsg *SatrefExtMsg, UINT8 *ucBuf, INT32 *iLen,  gl_TError *Error);
		
		/*
		*********************************************************************
		Reboot
		*********************************************************************
		*/				
		} else if(msgType == 13){
			status = system("./RTIS_KILL_ALL.sh");
		/*
		*********************************************************************
		Error messages
		*********************************************************************
		*/				
		} else if(msgType == 14){
			
			//forking, to have a child reading error messages.
			
			if(!hasForked) {
				hasForked = 1;
				pipe(fd);
				myPid = fork();
						
				if (myPid != 0)
				{	// parent: writing only, so close read-descriptor.
					close(fd[0]);
				} else {
					// child: reading only, so close the write-descriptor
					close(fd[1]);
				}
			}
			
			if(myPid != 0){
				// send the value on the write-descriptor.
				fetchError = 1;
				write(fd[1], &fetchError, sizeof(fetchError));
			} else	{
				// Open
				gl_RBuf_Open(gl_TKey Key, gl_TpRBuffer *pRBuf, gl_TError *Error);  //TODO: real params

				while(1) {
					cmdReceived = time(NULL);
					while(time(NULL) - cmdReceived < ERROR_FETCH_TIME_LENGTH) {
						// Read
						gl_RBuf_Get(gl_TpRBuffer pRBuf, void *pData, gl_TError *Error); //TODO: real params
						//SEND THIS IN A MESSAGE!
					}
					fetchError = 0;
					// Now read the data (will block). Process will wait indefinitely until next heartbeat.
					read(fd[0], &fetchError, sizeof(fetchError));
				}
			}
		}
	}
}
