<?php
//CREATION OF THE CONFIGURATION FILE
var conf = "; **************************************
; RTIM Configuration file
; Version 0.0.1
; Date updated: 2010-11-12
;	Severity filter :
;		0(0x00) = No error messages
;	    1(0x01) = F
;		3(0x03) = F+E
;		7(0x07) = F+E+W
;	   15(0x0F) = F+E+W+I
;	   31(0x1F) = All
;
;		Add 128(0x80) to the above = D
; **************************************
[MAIN]
SeverityFilter=".$_POST['RCFSeverityFilter'].
"StationId=".$_POST['RCFStationId'].
"RxIP_Address=".$_POST['RCFRxIP_Address'].
"RxPortNo=".$_POST['RCFRxPortNo'].
"RxSocketType=".$_POST['RCFRxSocketType'].
"RxIOTimeout=".$_POST['RCFRxIOTimeout'].
"RxConnectionTimeout=".$_POST['RCFRxConnectionTimeout'].
"RxRetryDelay=".$_POST['RxRetryDelay'].
"StationShortName=".htmlspecialchars($_POST['RCFStationShortName']).
"ReceiverPosition=".$_POST['RCFReceiverPositionX'].",".$_POST['RCFReceiverPositionY'].",".$_POST['RCFReceiverPositionZ'].
";
; **************************************
; GNSS Receiver Command Server Module
; **************************************
;
[GNSS_RXCMD_SRV]
SeverityFilter=".$_POST['GRCSMSeverityFilter'].
";
; **************************************
; GNSS RawData Server Module
; The sample rate is in msec and must be
; one of the following values:
; [10, 20, 40, 50, 100, 200, 500, 1000]
; **************************************
;
[GNSS_RAWDATA_SRV]
SeverityFilter=".$_POST['GRDSMeverityFilter'].
"SampleRate=".$_POST['GRDSMSampleRate'].
";
;
; **************************************
; GNSS Ephemerides Server Module
; **************************************
;
[GNSS_EPH_SRV]
SeverityFilter=".$_POST['GESMSeverityFilter'].
";
; **************************************
; Index Client Module
; **************************************
;
[INDEX_CLIENT]
SeverityFilter=".$_POST['ICMSeverityFilter'].
"TxIP_Address=".$_POST['ICMTxIP_Address'].
"TxPortNo=".$_POST['ICMTxPortNo'].
"TxSocketType=".$_POST['ICMTxSocketType'].
"TxIOTimeout=".$_POST['ICMTxIOTimeout'].
"TxConnectionTimeout=".$_POST['ICMTxConnectionTimeout'].
"TxRetryDelay=".$_POST['ICMTxRetryDelay'].
";
; **************************************
; Processing
; **************************************
;
[PROCESSING]
SeverityFilter=".$_POST['ProcessingSeverityFilter'].
"DopplerTolerance=".$_POST['ProcessingDopplerTolerance'].
"FilterFreq=".$_POST['ProcessingFilterFreq'].
";
; **************************************
; Output
; **************************************
;
[OUTPUT]
SeverityFilter=".$_POST['OuputSeverityFilter'].
"RootDirectory=".htmlspecialchars($_POST['OutputRootDirectory']);

echo conf;
?>
