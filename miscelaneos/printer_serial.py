import serial
s = serial.Serial(port='COM1')
s.open()
#ser=serial.Serial(0)

#define INICIO_IMPRESION				"\x1B\x40"
#define SONIDO_IMPRESION				""
#define LETRA_NORMAL						""
#define LETRA_GRANDE						""
#define LETRA_EXTRA_GRANDE				""
#define ACTIVA_LETRA_EXPANDIDA 		"\x1B\x61\x31\x1B\x72\x31\x1B\x21\x30"
#define DESACTIVA_LETRA_EXPANDIDA 	"\x1B\x21\x01\x1B\x72\x30\x1B\x61\x30"
#define ACTIVA_LETRA_ILUMINADA		""
#define DESACTIVA_LETRA_ILUMINADA	""
#define ACTIVA_LETRA_SUBRAYADA		"\x1B\x2D\x32"
#define DESACTIVA_LETRA_SUBRAYADA	"\x1B\x2D\x30"
#define FIN_DE_IMPRESION				"\x1B\x64\x07"
#define CORTA_HOJA						"\x1D\x56\x30"
#define JUSTIFICAR_CENTER				"\x1B\x61\x31"
#define JUSTIFICAR_LEFT					"\x1B\x61\x30"


ser.write('\x1B\x40')
ser.write('\x1B\x61\x31\x1B\x72\x31\x1B\x21\x30')
ser.write('   PDVSA  \n\n')
ser.write('\x1B\x21\x01\x1B\x72\x30\x1B\x61\x30')

ser.write('\x1B\x21\x01\x1B\x72\x30\x1B\x61\x30')
ser.write('Fecha'+'11-04-2001\n')
ser.write('Pedido'+'89652\n')
ser.write('Cliente'+'PDVSA GAS\n')
ser.write('Transporte'+'ENT\n')
ser.write('Cisterna'+'CIST001\n')
ser.write('Chuto'+'CHUT001\n')
ser.write('Cedula'+'17167482\n')

ser.write('\x1B\x61\x31\x1B\x72\x31\x1B\x21\x30')
ser.write('\x1B\x2D\x32')
ser.write('              \n')
ser.write('\x1B\x2D\x30')
ser.write('Despacho:'+' 45962')
ser.write('\x1B\x21\x01\x1B\x72\x30\x1B\x61\x30')
ser.write('       PEDIDO CONFIRMADO')
ser.write('              \n')
ser.write('              \n')
ser.write('              \n')
ser.write('\x1B\x64\x07')
ser.write('\x1D\x56\x30')
ser.close()

