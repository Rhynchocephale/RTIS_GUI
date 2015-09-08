#define GL_PC_LINUX
#define CONFIG_FILE_SIZE = 335
#define HEADER_SIZE = 20
#define ERROR_FETCH_TIME_LENGTH = 300
#define CONFIG_FILE = "config.conf"
#include <gl_App.h>
#include <stdio.h>


//Accepts two argument: first is the number of the station, next is 0 for processes or 1 for monitor table
INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="commandReader";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
	
	INT32					i;
	INT32					n;
	INT8					hasForked=0;
	INT8					fetchError;
	INT32					fd[2];
	
	gl_TSatrefExtMsg		satrefMsg;
	gl_TSatrefExtHeader		msgHead;
	UINT8*					msgBody;
	UINT8*					msgContent;
	
	pid_t 					pid;
	pid_t					myPid;
	
	gl_TError 				Error;
	gl_TDevice 				ErrDev[RTIS_MAX_NO_OF_ERROR_DEV];
	gl_TDevice 				Dev;
	
	GL_DLL_CALL_TYPE_INT32	serverHandle;
	
	const char *			delimitor=",";
		
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
	Connecting to socket
	*********************************************************************
	*/
	serverHandle = gl_Socket_Server_Start(gl_TDevice *ListenSocket, INT32 iSocketType, char  *szHost,  char *szService, INT32 iProtocol, INT32 iFormat, 
INT32 iConnectTimeout, INT32 iBlocked, INT32 iBackLog, gl_TError *Error);

	/*
	*********************************************************************
	Connecting to the different tables
	*********************************************************************
	*/
	iProcStatus = gl_Shm_Open(RTIS_KEY_PROC_TABLE, &pProcTbl, &Error);
	while (iProcStatus == GL_ERROR)
	{
		gl_Error_Set(szFunctionName,GL_SEVERITY_ERROR,GL_APPLICATION_ERROR,"DDS_001_02","Unable to open process table. Waiting...", &Error);
		gl_Sleep(5,0);
		iProcStatus = gl_Shm_Open(RTIS_KEY_PROC_TABLE, &pProcTbl, &Error);
	}
	
	iMonStatus = gl_Shm_Open(RTIS_KEY_MON_TABLE, &pMonTbl, &Error);
	while (iMonStatus == GL_ERROR)
	{
		gl_Error_Set(szFunctionName,GL_SEVERITY_ERROR,GL_APPLICATION_ERROR,"DDS_001_02","Unable to open monitor table. Waiting..", &Error);
		gl_Sleep(5,0);
		iMonStatus = gl_Shm_Open(RTIS_KEY_MON_TABLE, &pMonTbl, &Error);
	}
	
	iInterStatus = gl_Shm_Open(RTIS_KEY_INTERFACE_TABLE, &pInterfaceTbl, &Error);
	while (iInterStatus == GL_ERROR)
	{
		gl_Error_Set(szFunctionName,GL_SEVERITY_ERROR,GL_APPLICATION_ERROR,"DDS_001_02","Unable to open interface table. Waiting ...", &Error);
		gl_Sleep(1,0);
		iInterStatus = gl_Shm_Open(RTIS_KEY_INTERFACE_TABLE, &pInterfaceTbl, &Error);
	}

	/*
	*********************************************************************
	Listening for input
	*********************************************************************
	*/
	while(1){
		msgContent = read(serverHandle);
		
		READ MESSAGE TYPE
		
		
		/*
		*********************************************************************
		Config file
		*********************************************************************
		*/
		if(msgType == 10){		
			
			fp=fopen(CONFIG_FILE, "w");
			if(fp == NULL)
				exit(-1);
			
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
			fprintf(fp, "SeverityFilter=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT8);
			fprintf(fp, "StationId=%d\n",			msgContent[iOffset]);			iOffset += sizeof(UINT16);
			fprintf(fp, "RxIP_Address=%s\n",		msgContent[iOffset]);			iOffset += 15*sizeof(UINT8);
			fprintf(fp, "RxPortNo=%d\n",			msgContent[iOffset]);			iOffset += sizeof(UINT16);
			fprintf(fp, "RxSocketType=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT8);
			fprintf(fp, "RxIOTimeout=%d\n",			msgContent[iOffset]);			iOffset += sizeof(UINT16);
			fprintf(fp, "RxConnectionTimeout=%d\n",	msgContent[iOffset]);			iOffset += sizeof(UINT16);
			fprintf(fp, "RxRetryDelay=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT16);
			fprintf(fp, "StationShortName=%s\n",	msgContent[iOffset]);			iOffset += 3*sizeof(UINT8);
			fprintf(fp, "ReceiverPosition=%.3f,",	msgContent[iOffset]);			iOffset += sizeof(DOUBLE64);
			fprintf(fp, "%.3f,",					msgContent[iOffset]);			iOffset += sizeof(DOUBLE64);
			fprintf(fp, "%.3f\n",					msgContent[iOffset]);			iOffset += sizeof(DOUBLE64);
			fprintf(fp, ";\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, "; GNSS Alarm Server Module\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, ";\n");
			fprintf(fp, "[GNSS_ALARM_SRV]\n");
			fprintf(fp, "SeverityFilter=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT8);
			fprintf(fp, "Log=%s\n",					msgContent[iOffset]?"on":"off");iOffset += sizeof(UINT8);
			fprintf(fp, "Console=%s\n",				msgContent[iOffset]?"on":"off");iOffset += sizeof(UINT8);
			fprintf(fp, "Gui=%s\n",					msgContent[iOffset]?"on":"off");iOffset += sizeof(UINT8);
			fprintf(fp, ";\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, "; GNSS Receiver Command Server Module\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, ";\n");
			fprintf(fp, "[GNSS_RXCMD_SRV]\n");
			fprintf(fp, "SeverityFilter=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT8);
			fprintf(fp, ";\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, "; GNSS RawData Server Module\n");
			fprintf(fp, "; The sample rate is in msec and must be\n");
			fprintf(fp, "; one of the following values:\n");
			fprintf(fp, "; [10, 20, 40, 50, 100, 200, 500, 1000]\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, ";\n");
			fprintf(fp, "[GNSS_RAWDATA_SRV]\n");
			fprintf(fp, "SeverityFilter=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT8);
			fprintf(fp, "SampleRate=%d\n",			msgContent[iOffset]);			iOffset += sizeof(UINT16);
			fprintf(fp, ";\n");
			fprintf(fp, ";\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, "; GNSS Ephemerides Server Module\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, ";\n");
			fprintf(fp, "[GNSS_EPH_SRV]\n");
			fprintf(fp, "SeverityFilter=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT8);
			fprintf(fp, ";\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, "; Index Client Module\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, ";\n");
			fprintf(fp, "[INDEX_CLIENT]\n");
			fprintf(fp, "SeverityFilter=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT8);
			fprintf(fp, "TxIP_Address=%s\n",		msgContent[iOffset]);			iOffset += 15*sizeof(UINT8);
			fprintf(fp, "TxPortNo=%d\n",			msgContent[iOffset]);			iOffset += sizeof(UINT16);
			fprintf(fp, "TxSocketType=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT8);
			fprintf(fp, "TxIOTimeout=%d\n",			msgContent[iOffset]);			iOffset += sizeof(UINT16);
			fprintf(fp, "TxConnectionTimeout=%d\n",	msgContent[iOffset]);			iOffset += sizeof(UINT16);
			fprintf(fp, "TxRetryDelay=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT16);
			fprintf(fp, ";\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, "; Processing\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, ";\n");
			fprintf(fp, "[PROCESSING]\n");
			fprintf(fp, "SeverityFilter=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT8);
			fprintf(fp, "DopplerTolerance=%.1f\n",	msgContent[iOffset]);			iOffset += sizeof(DOUBLE64);
			fprintf(fp, "FilterFreq=%.1f\n",		msgContent[iOffset]);			iOffset += sizeof(DOUBLE64);
			fprintf(fp, ";\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, "; Output\n");
			fprintf(fp, "; **************************************\n");
			fprintf(fp, ";\n");
			fprintf(fp, "[OUTPUT]\n");
			fprintf(fp, "SeverityFilter=%d\n",		msgContent[iOffset]);			iOffset += sizeof(UINT8);
			fprintf(fp, "RootDirectory=%s\n",		msgContent[iOffset]);			iOffset += 255*sizeof(UINT8);
			fprintf(fp, "\n");
			
			fclose(fp);
					
		/*
		*********************************************************************
		Processes
		*********************************************************************
		*/
		} else if(msgType == 11){
			
			iProcStatus = system("clear");
			for(n=0; n<RTIS_MAX_NO_OF_MAIN_PROC;n++)
			{
				iProcStatus = gl_Shm_Get(pProcTbl, &ProcessTableInfo, n, &Error);
				if (iProcStatus == GL_ERROR)
					goto Proc_Init;

				if (ProcessTableInfo.Pid > 0)
					rtis_PrintTableRow(n, RTIS_PROC_TABLE, &ProcessTableInfo, "");  //WHAT IS THIS FUNCTION? REPLACE THAT WITH MESSAGE
			}
			iProcStatus = system("clear");
		
		/*
		*********************************************************************
		Monitor
		*********************************************************************
		*/	
		} else if(msgType == 12){

			iInterStatus = gl_Shm_Get(pInterfaceTbl, &InterfaceTbl, GL_SHM_SINGLE, &Error);
			if (iStatus == GL_ERROR)
				goto Monitor_Init;

			iStatus = system("clear");

			iNoOfEpochs = 0;
			iMonStatus = gl_Shm_Get(pMonTbl, &MonTbl, GL_SHM_SINGLE, &Error);
			if (iMonStatus == GL_ERROR)
				goto Monitor_Init;

			iInterStatus = gl_Shm_Get(pInterfaceTbl, &InterfaceTbl, GL_SHM_SINGLE, &Error);
			if (iInterStatus == GL_ERROR)
				goto Monitor_Init;

			iInterStatus = system("clear");

			n=0;

			/*
			*********************************************************************
			Making header
			*********************************************************************
			*/
			INT16_BIT16(&msgHead[n],sRefId);  n +=sizeof(INT16);
			msgHead[n] = ucMsgClass; n +=sizeof(UINT8);
			msgHead[n] = CMD_CONFIG_MSG_TYPE; n +=sizeof(UINT8);
			INT32_BIT32(&msgHead[n],lGpsSec); n +=sizeof(INT32); //???
			INT16_BIT16(&msgHead[n],sGpsmSec); n +=sizeof(INT16); //???
			INT16_BIT16(&msgHead[n],2*sizeof(INT16)+4*sizeof(INT32)+4*sizeof(MYSTERY)); n +=sizeof(INT16);
			
				/*
			*********************************************************************
			Making body
			*********************************************************************
			*/
			monitorBody[n] = InterfaceTbl.szIPAddress; n += //MYSTERY;
			INT16_BIT16(&monitorBody[n],InterfaceTbl.iPortNo);  n +=sizeof(INT16);
			monitorBody[n] = InterfaceTbl.szCmdInterfaceModule; n += //MYSTERY;
			monitorBody[n] = InterfaceTbl.szRawDataInterfaceModule; n += //MYSTERY;
			monitorBody[n] = InterfaceTbl.szEphDataInterfaceModule; n += //MYSTERY;
			INT16_BIT16(&monitorBody[n],InterfaceTbl.iSampleRate);  n +=sizeof(INT16);
			INT32_BIT32(&monitorBody[n],MonTbl.iNoOfRawMeas);  n +=sizeof(INT32);
			INT32_BIT32(&monitorBody[n],MonTbl.iNoOfLostRawMeas);  n +=sizeof(INT32);
			INT32_BIT32(&monitorBody[n],MonTbl.iNoOfIQMeas);  n +=sizeof(INT32);
			INT32_BIT32(&monitorBody[n],MonTbl.iNoOfLostIQMeas);  n +=sizeof(INT32);
			INT32_BIT32(&monitorBody[n],MonTbl.iOther);  n +=sizeof(INT32);
			
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
