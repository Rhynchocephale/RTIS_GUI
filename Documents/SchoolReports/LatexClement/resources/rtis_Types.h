/*
********************************************************************************
Purpose        : RTIS Data Types
********************************************************************************
File ID        : rtis_Types.h
Part of        : RTIS
Language       : Ansi C
Written by     : Rune I. Hanssen
Last update    : $Date: 2012-08-17 06:28:19 $  $Author: schseb $
Revision       : $Revision: 1.18 $
Remarks        :
********************************************************************************
Revision log   : $Log: not supported by cvs2svn $
Revision log   : Revision 1.17  2012/02/17 08:15:20  schseb
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.16  2011/09/26 08:15:22  schseb
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.15  2011/08/11 08:14:39  rune
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.14  2011/08/09 06:44:56  rune
Revision log   : Added Scintillation Index Client
Revision log   :
Revision log   : Revision 1.13  2011/08/08 07:29:52  schseb
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.12  2011/06/28 11:06:55  schseb
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.11  2011/05/20 13:40:11  rune
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.10  2011/05/19 07:48:00  rune
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.9  2011/05/19 06:18:49  rune
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.8  2011/05/11 07:23:54  rune
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.7  2011/04/29 11:07:38  schseb
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.6  2011/04/04 21:24:58  rune
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.5  2011/04/04 09:24:36  schseb
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.4  2011/04/04 07:28:01  schseb
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.3  2011/03/14 10:58:03  rune
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.2  2011/03/14 10:39:03  rune
Revision log   : *** empty log message ***
Revision log   :
Revision log   : Revision 1.1  2011/03/14 07:37:24  rune
Revision log   : Initial version
Revision log   :
********************************************************************************
*/

#ifndef RTIS_TYPES_H_
#define RTIS_TYPES_H_
/*
*******************************************************************************
RTIS Process Table
*******************************************************************************
*/
typedef struct
{
	pid_t 			Pid;
	char	 	    IntName[GL_PROCESS_NAME_SIZE];
	INT32			iStartTime;
	INT8 			ActionFlag;
}rtis_TProcessTableInfo;
/*
*******************************************************************************
RTIS Monitor Table
*******************************************************************************
*/
typedef struct
{
	UINT32			uiCurWeek;
	UINT32			uiCurTow;
	INT32			iCurDataTbl;
	INT32			iReadyDatasetCount;
	INT32			iCurIndex;
	INT32			iNoOfRawMeas;
	INT32			iNoOfIQMeas;
	INT32			iNoOfLostRawMeas;
	INT32			iNoOfLostIQMeas;
	INT32			iOther;
	INT32			iTotNoOfRawMeas;
	INT32			iTotNoOfIQMeas;
	INT32			iTotOther;
}rtis_TMonitorInfo;

typedef struct
{
	FLOAT64 		dSigmaPhi;
}rtis_TMonitorResults;

/*
*******************************************************************************
RTIS Interface Table
*******************************************************************************
*/
typedef struct
{
	char			szIPAddress[GL_IPADDRESS_SIZE];
	INT32			iPortNo;
	char			szCmdInterfaceModule[6];
	char			szRawDataInterfaceModule[6];
	char			szEphDataInterfaceModule[6];
	INT32			iSampleRate;
}rtis_TInterfaceInfo;
/*
******************************************************
Definition of the config table
******************************************************
 */
typedef struct
{
	UINT8     	ucSeverityFilter;								/* Severity Filter */
	UINT8		ucInputDevice;									/* RTIS_INPUT_SOCKET, RTIS_INPUT_FILE */
	char		szInputFileName[GL_FILENAME_SIZE];
	UINT16		usStationId;
	char		szRxIpAddress[GL_IPADDRESS_SIZE];
	UINT16		usRxPortNo;
	INT32		iRxSocketType;
	INT32		iRxConnectionTimeout;
	INT32		iRxIOTimeout;
	INT32		iRxRetryDelay;
	char 		szStationShortName[5];
	FLOAT64		dPosRecCart[3];
}rtis_TMain_Process;

typedef struct
{
	UINT8     	ucSeverityFilter;								/* Severity Filter */
	INT32		iLog;
	INT32		iConsole;
	INT32		iGui;
}rtis_TAlarmSrv_Process;

typedef struct
{
	UINT8     	ucSeverityFilter;								/* Severity Filter */
	INT32		iSampleRate;									/* Sample rate in msec. Must be [10, 20, 40, 50, 100, 200, 500, 1000] */
	INT32		iMaxMeas;										/* updated automatically from the value of iSampleRate */
	char		szSampleRate[8];
}rtis_TGnss_RawdataSrv_Process;

typedef struct
{
	UINT8     	ucSeverityFilter;								/* Severity Filter */
}rtis_TGnss_EphSrv_Process;

typedef struct
{
	UINT8     	ucSeverityFilter;								/* Severity Filter */
}rtis_TGnss_RxCmd_Process;

typedef struct
{
	UINT8     	ucSeverityFilter;								/* Severity Filter */
	char        root_directory[GL_FILENAME_SIZE];
}rtis_SBF_Output_Process;

typedef struct
{
	UINT8     	ucSeverityFilter;								/* Severity Filter */
	char		szTxIpAddress[GL_IPADDRESS_SIZE];
	UINT16		usTxPortNo;
	INT32		iTxConnectionTimeout;
	INT32		iTxIOTimeout;
	INT32		iTxRetryDelay;
	INT32		iTxSocketType;
}rtis_TIndex_Client_Process;

typedef struct
{
	UINT8     	ucSeverityFilter;								/* Severity Filter */
	FLOAT64 	dPhaseDopplerTolerance;
	FLOAT64		dFreqFilter;
}rtis_TProcessing;

typedef struct
{
	UINT8		ucEnable;
	UINT8     	ucSeverityFilter;								/* Severity Filter */
	char		szTxIpAddress[GL_IPADDRESS_SIZE];
	UINT16		usTxPortNo;
	INT32		iTxConnectionTimeout;
	INT32		iTxIOTimeout;
	INT32		iTxRetryDelay;
	INT32		iTxSocketType;
}rtis_TSatref_Server_Process;

typedef struct
{
	rtis_TMain_Process				Main_Process;
	rtis_TGnss_RxCmd_Process		RxCmdSrv;
	rtis_TGnss_RawdataSrv_Process  	RawDataSrv;
	rtis_TGnss_EphSrv_Process		EphDataSrv;
	rtis_TIndex_Client_Process		IndexCli;
	rtis_TSatref_Server_Process		SatrefSrv;
	rtis_TProcessing				Processing;
	rtis_TAlarmSrv_Process			AlarmSrv;
	rtis_SBF_Output_Process         Output_Process;
}rtis_TConfig;
/*
******************************************************
Definition of the child info table
******************************************************
 */
typedef struct
{
	pid_t 		Pid;
	INT32 		ProcIdx;
	char 		Name[GL_PROCESS_NAME_SIZE];
	void 		(*pProcess)(void);
	INT32	 	RestartFlag;
}rtis_TChildInfo;
/*
******************************************************
Definition of the command message
******************************************************
*/
typedef struct
{
	char szRxCmd[RTIS_COMMAND_SIZE];
} rtis_TRxCommand;
/*
******************************************************
Definition of the measurement epoch
******************************************************

typedef struct
{
	FLOAT64 dS1;
	FLOAT64 dS2;
	FLOAT64 dL1;
	FLOAT64 dL2;
	FLOAT64 dDoppler1;
	FLOAT64 dDoppler2;
} rtis_TMeasurementEpoch;
*/
/*
******************************************************
Definition of the measurement epoch
******************************************************

typedef struct
{
	FLOAT64 dSI1;
	FLOAT64 dSI2;
	FLOAT64 CarrierPhaseLSB[RTIS_MAX_NO_FREQUENCIES];
} rtis_TIQCorrEpoch;
*/
/*
******************************************************
Definition of the measurement epoch table
******************************************************

typedef struct
{
	INT32   iWeekNo;
	INT32   iTow;
	FLOAT64 dTimeTag;
	INT32   CumClkJumps;
	FLOAT64 dFreqNo[RTIS_MAX_NO_GNSS];					// GLONASS Frequency no
	rtis_TMeasurementEpoch 	GnssData[RTIS_MAX_NO_GNSS];
	rtis_TIQCorrEpoch IQCorrData[RTIS_MAX_NO_GNSS];
} rtis_TEpoch;
*/
/*
******************************************************
Definition of the measurement epoch table
******************************************************
*/
typedef struct
{
	INT32   			 iEpochWeekNo;
	INT32   			 iEpochTow;
	FLOAT64 			 dEpochTimeTag;
	gl_TRibex_GpsObs	 Obs;
	gl_TRibex_IQCorr 	 IQCorr;
} rtis_TGpsEpoch;

typedef struct
{
	INT32   			 iEpochWeekNo;
	INT32   			 iEpochTow;
	FLOAT64 			 dEpochTimeTag;
	gl_TRibex_GlonassObs Obs;
	gl_TRibex_IQCorr 	 IQCorr;
} rtis_TGloEpoch;

/*
typedef struct
{
	INT32   			 iEpochWeekNo;
	INT32   			 iEpochTow;
	FLOAT64 			 dEpochTimeTag;
	gl_TRibex_GalileoObs Obs;
	gl_TRibex_IQCorr 	 IQCorr;
} rtis_TGalEpoch;
*/
/*
******************************************************
Definition of the PRN measurement interval table
******************************************************
*/

typedef struct
{
	INT32			iNoOfValidEpochs;
	rtis_TGpsEpoch 	Epoch[RTIS_MAX_NO_OF_EPOCHS];
} rtis_TGpsInterval;
typedef struct
{
	INT32			iNoOfValidEpochs;
	rtis_TGloEpoch 	Epoch[RTIS_MAX_NO_OF_EPOCHS];
} rtis_TGloInterval;


/*
******************************************************
Definition of the PRN measurement extended interval table
******************************************************
*/
typedef struct
{
	rtis_TGpsInterval 	ExtendEpochInterval[RTIS_MAX_NO_OF_INTERVALS];
} rtis_TGpsProcInterval;
typedef struct
{
	rtis_TGloInterval 	ExtendEpochInterval[RTIS_MAX_NO_OF_INTERVALS];
} rtis_TGloProcInterval;

/*
******************************************************
Definition of the Satellite info table
******************************************************
*/
typedef struct
{
	UINT32 	  uiGpsSec;
    FLOAT32   fAzimuth;   // 0.01 degrees
    FLOAT32   fElevation; // 0.01 degrees
}rtis_TSatInfo;

typedef struct
{
	UINT8          ucNoOfPrn;
	rtis_TSatInfo  SatInfo[GL_SCINT_MAX_GNSS_PRN_NO];
}rtis_TSatInfoTbl;

/*
******************************************************
Definition of the total satellite info table for all
constellations
******************************************************
*/
typedef struct
{
	rtis_TSatInfoTbl GPS;
	rtis_TSatInfoTbl GLO;
	rtis_TSatInfoTbl GAL;
}rtis_TSatSystem_Tbl;

/*
******************************************************
Information of Tau_GPS and Tau_c for all visible GLONASS
satellites from GLOTime block (ID4036)
******************************************************
*/
typedef struct
{
	FLOAT32  	dTau_GPS;   // difference with respect to GPS time [sec]
	FLOAT64  	dTau_c;     // GLONASS time scale correction to UTC(SU) [sec]
}rtis_TGloTime_Tbl;


/*
******************************************************
Definition of time-lines in the scintillation-index file
******************************************************
*/
typedef struct
{
	INT32 iYear;
	INT32 iMonth;
	INT32 iDay;
	INT32 iHour;
	INT32 iMinute;
	FLOAT32 dSecond;
	INT32 iNumRecords;
} rtis_TTimeTag;
/*
******************************************************
Definition of The index lines in the scintillation-index file
******************************************************
*/
typedef struct
{
	INT32			iSatId;
	FLOAT32			dIPPlat;
	FLOAT32			dIPPlon;
	FLOAT32			dElev;
	FLOAT32			dS4L1;
	FLOAT32			dSigmaPhiL1;
	FLOAT32			dSpectrumSlopeL1;
	FLOAT32			dS4L2;
	FLOAT32			dSigmaPhiL2;
	FLOAT32			dSpectrumSlopeL2;
} rtis_TIndexEpoch; 

typedef struct
{

    UINT8        ucSVID;
    FLOAT64   dPseudorange;
    FLOAT64   dCarrierPhase;
    FLOAT64   dDoppler;
    FLOAT64   dSNR;
    INT8          ucObsInfo;             // contains pseudorange measurement is smoothed or not
    FLOAT64   	  dFreqNr;
    INT8          ucGPS_Header_flags;
    INT8          ucGPS_L1_flags;
    INT8          ucGPS_L2_flags;
    INT8          ucGPS_L5_flags;
    INT8          ucGLO_Header_flags;
    INT8          ucGLO_L1_flags;
    INT8          ucGLO_L2_flags;

    INT8          ucPseudorange_valid;   // valid : 1   invalid : 0
    INT8          ucCarrierPhase_valid;  // valid : 1   invalid : 0
    INT8          ucDoppler_valid;         // valid : 1   invalid : 0
    INT8          ucSNR_valid;            // valid : 1   invalid : 0

} rtis_SubBlk_info;

/*
******************************************************
Definition of Plot Config
******************************************************
*/

typedef struct
{
	char		szName[5];
	FLOAT64		dLong;
	FLOAT64		dLat;
}rtis_TStationInfo;

typedef struct
{
	UINT8     	ucSeverityFilter;								/* Severity Filter */
	INT32		iNoOfStations;
	INT32 		iFromYear;
	INT32 		iToYear;
	INT32 		iFromDOY;
	INT32 		iToDOY;
	INT32		iSecBetweenEpochs;
	INT32		iEventGapLimit;
	FLOAT64		dElevationMask;
	FLOAT64 	dL1_SigmaPhi_Limit;
	FLOAT64 	dL1_S4_Limit;
	FLOAT64 	dL2_SigmaPhi_Limit;
	FLOAT64 	dL2_S4_Limit;
	UINT8       uFlag_L1_SigmaPhi;
	UINT8       uFlag_L1_S4;
	UINT8       uFlag_L2_SigmaPhi;
	UINT8       uFlag_L2_S4;
	INT32		iPlotInterval;
	rtis_TStationInfo Station[RTIS_MAX_NO_OF_STATIONS];
}rtis_TPlotConfig;
/*
******************************************************
Definition of Scint detection struct
******************************************************
*/
typedef struct
{
	INT32 	 		iEventStart;
	INT32			iEventEnd;
	INT32			iZeroCount;
	INT32			iCount;
	FLOAT64			dMax;
	FLOAT64			dMin;
	FLOAT64			dMean;
	FLOAT64			dMedian;
	FLOAT64			dStdDev;
	FLOAT64			dSum;
}rtis_TScintDet;

typedef struct
{
	INT32	iCurPtr;
	rtis_TScintDet ScintDet[RTIS_MAX_EVENT_INTERVAL];
}rtis_TScintDetTbl;

/*
******************************************************
Definition the structure containing data which will
be send to RTIM using a Satref message server.
******************************************************
*/
#include "rtis_ScintillationIndex_Types.h"

#endif /* RTIS_TYPES_H_ */
