#define GL_PC_LINUX
#define CONFIG_FILE_SIZE 		335
#define HEADER_SIZE				20
#define CMD_CONFIG_MSG_TYPE  	10
#define NB_OF_FIELDS 			34
#define TIMEOUT 				20
#define DEFAULT_CONNECTION_PORT 20000

#include <gl_App.h>
#include <rtis_App.h>
#include <stdio.h>

INT32 main(int argc, char *argv[])
{
	char 					szApplicationName[]="configSender";
	char 					szProcessName[]="";
	char 					szFunctionName[]="main";
	
	INT8					ucMsgClass = 26;
	INT8					ucMsgType = 20;
	INT16					sRefId = 120;
	INT32					iStatus;
	INT32					i;
	INT32					n;
	
	gl_TSatrefExtMsg		satrefMsg;
	UINT8					ucBuf[GL_SATREF_DATA_BUFFER_SIZE];  //NOT SURE
	
	pid_t 					pid;
	
	gl_TError 				Error;
	gl_TDevice 				ErrDev[RTIS_MAX_NO_OF_ERROR_DEV];
	gl_TDevice 				Socket;
	
	char					ipAdressIWantToConnectTo;
		
	/*
	*********************************************************************
	Initialise the alarm system
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
	ErrDev[0].iType = GL_DEVICE_TYPE_ALARM_LOG;
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
	Checking number of arguments
	*********************************************************************
	*/
	if(argc != NB_OF_FIELDS+1){
		printf("Expecting %i arguments, %i received.",NB_OF_FIELDS+1,argc-1);
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
	satrefMsg.HeaderData.ucMsgType = ucMsgType;
	INT32_BIT32(&satrefMsg.HeaderData.lGpsSec,time(NULL));
	INT16_BIT16(&satrefMsg.HeaderData.sGpsmSec,0);
	INT16_BIT16(&satrefMsg.HeaderData.sLen,300*sizeof(UINT8)+9*sizeof(INT16)+5*sizeof(FLOAT64));
	
	/*
	*********************************************************************
	Making body
	*********************************************************************
	*/
	n=0;

	satrefMsg.ucData[n] = argv[1]; n +=sizeof(UINT8); 					//RCF severity filter
	INT16_BIT16(&satrefMsg.ucData[n],argv[2]);  n +=sizeof(INT16); 		//RCF station id
	satrefMsg.ucData[n] = argv[3]; n +=15*sizeof(UINT8);					//RCF IP as a string
	INT16_BIT16(&satrefMsg.ucData[n],argv[4]);  n +=sizeof(INT16); 		//RCF port no
	satrefMsg.ucData[n] = argv[5]; n +=sizeof(UINT8); 					//RCF socket type
	INT16_BIT16(&satrefMsg.ucData[n],argv[6]);  n +=sizeof(INT16); 		//RCF IO timeout
	INT16_BIT16(&satrefMsg.ucData[n],argv[7]);  n +=sizeof(INT16); 		//RCF connection timeout
	INT16_BIT16(&satrefMsg.ucData[n],argv[8]);  n +=sizeof(INT16); 		//RCF retry delay
	satrefMsg.ucData[n] = argv[9]; n +=3*sizeof(UINT8);					//RCF station short name as a string
	FLOAT64_BIT64(&satrefMsg.ucData[n],argv[10]);  n +=sizeof(FLOAT64);//RCF receiver position X
	FLOAT64_BIT64(&satrefMsg.ucData[n],argv[11]);  n +=sizeof(FLOAT64);//RCF receiver position Y
	FLOAT64_BIT64(&satrefMsg.ucData[n],argv[12]);  n +=sizeof(FLOAT64);//RCF receiver position Z
	satrefMsg.ucData[n] = argv[13]; n +=sizeof(UINT8); 					//GASM severity filter
	satrefMsg.ucData[n] = argv[14]; n +=sizeof(UINT8); 					//GASM log
	satrefMsg.ucData[n] = argv[15] n +=sizeof(UINT8); 					//GASM console
	satrefMsg.ucData[n] = argv[16]; n +=sizeof(UINT8); 					//GASM gui
	satrefMsg.ucData[n] = argv[17]; n +=sizeof(UINT8); 					//GRCSM severity filter
	satrefMsg.ucData[n] = argv[18]; n +=sizeof(UINT8); 					//GRDSM severity filter
	INT16_BIT16(&satrefMsg.ucData[n],argv[19]);  n +=sizeof(INT16); 		//GRDSM sample rate
	satrefMsg.ucData[n] = argv[20]; n +=sizeof(UINT8); 					//GESM severity filter
	satrefMsg.ucData[n] = argv[21]; n +=sizeof(UINT8); 					//ICM severity filter
	satrefMsg.ucData[n] = argv[22]; n +=15*sizeof(UINT8);				//ICM IP as a string
	INT16_BIT16(&satrefMsg.ucData[n],argv[23]);  n +=sizeof(INT16);	 	//ICM port no
	satrefMsg.ucData[n] = argv[24]; n +=sizeof(UINT8); 					//ICM socket type
	INT16_BIT16(&satrefMsg.ucData[n],argv[25]);  n +=sizeof(INT16);	 	//ICM IO timeout
	INT16_BIT16(&satrefMsg.ucData[n],argv[26]);  n +=sizeof(INT16);	 	//ICM connection timeout
	INT16_BIT16(&satrefMsg.ucData[n],argv[27]);  n +=sizeof(INT16); 	//ICM retry delay
	satrefMsg.ucData[n] = argv[28]; n +=sizeof(UINT8); 					//Processing severity filter
	FLOAT64_BIT64(&satrefMsg.ucData[n],argv[29]);  n +=sizeof(FLOAT64);	//Processing Doppler tolerance
	FLOAT64_BIT64(&satrefMsg.ucData[n],argv[30]);  n +=sizeof(FLOAT64);	//Processing filter frequency
	satrefMsg.ucData[n] = argv[31]; n +=sizeof(UINT8); 					//Output severity filter
	satrefMsg.ucData[n] = argv[32]; n +=255*sizeof(UINT8);				//Output path as a string
	
	iStatus = gl_Socket_Server_Connect(&Socket, GL_DEVICE_TYPE_TCP_CLIENT_SOCKET, ipAdressIWantToConnectTo, DEFAULT_CONNECTION_PORT, GL_DEVICE_PROTOCOL_SATREF, GL_DEVICE_FORMAT_BINARY, TIMEOUT, GL_DEVICE_NONBLOCKED, &Error);
	iStatus = gl_Satref_WriteMsg(&Socket, &satrefMsg, &Error);

	iStatus = gl_Satref_ReadMsg(&Socket, &satrefMsg, &Error);
	if(iStatus != GL_OK){
		printf("Communication error");
		return 2;
	}
	//Waiting for an acknowledgement of the command
	while(i<TIMEOUT){
		if(satrefMsg.HeaderData.sLen == 0){
			gl_Sleep(1,0);
			iStatus = gl_Satref_ReadMsg(&Socket, &satrefMsg, &Error);
			i++;
		} else {
			printf("Success");
			return 0;
		}
	}
	printf("Timeout");
	return 1;
}
