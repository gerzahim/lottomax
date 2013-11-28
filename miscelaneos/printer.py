import SocketServer
import serial
import MySQLdb as mdb

#leyendo Archivo de Configuracion
file = open( "c:\parametros.cvs", "r" )
array = []
for line in file:
    array.append( line )
file.close()

#print array

#asignando el valor del archivo
id_taquilla = array[0].replace('\n', '')
tipo_impresora = array[1].replace('\n', '')
ip_server = array[2].replace('\n', '')
bd = array[3].replace('\n', '')
user = array[4].replace('\n', '')
passw = array[5].replace('\n', '')
puerto = array[6].replace('\n', '')
baudrate = array[7].replace('\n', '')

nombre_usuario = ''


#abriendo conexion a la base de datos
#con = mdb.connect('localhost', 'root', '', 'lottomax')
con = mdb.connect(ip_server, user, passw, bd)
cursor = con.cursor()



class MyTCPHandler(SocketServer.BaseRequestHandler):
    """
    The RequestHandler class for our server.

    It is instantiated once per connection to the server, and must
    override the handle() method to implement communication to the
    client.
    """
    
    def handle(self):
        # self.request is the TCP socket connected to the client
        self.data = self.request.recv(1024).strip()
        print "{} wrote:".format(self.client_address[0])
        print self.data
        DefinirTicket()
        
        # just send back the same data, but upper-cased
        self.request.sendall(self.data.upper())

def DefinirTicket():
    datos_ticket = GetLastTicket(id_taquilla)
    
    parametros = GetDatosParametros()

    GetDetalleTicketByIdticket(datos_ticket['id_ticket'])

    #definiendo Encabezado ticket
    data1 = "SISTEMA LOTTOMAX"
    data1 += "\\n"
    data1 += "\\nAGENCIA: "+parametros['nombre_agencia']
    data1 += "\\n"
    data1 += "\\nTICKET: "+datos_ticket['id_ticket']
    data1 += "\\n"
    data1 += "\\nSERIAL: "+datos_ticket['serial']
    data1 += "\\n"
    data1 += "\\nFECHA: "+datos_ticket['fecha_hora']
    data1 += "\\n"
    data1 += "\\nTAQUILLA: "+id_taquilla
    data1 += "\\n"
    data1 += "\\nVENDEDOR: "+datos_ticket['nombre_usuario']
    data1 += "\\n"
    data1 += "-----------------------------"

    # Obtiene los numeros jugados No zodiacales
    resulta = GetDetalleTicketByIdticket(datos_ticket['id_ticket'])

    data1 += resulta['data1']

    # Obtiene los numeros jugados zodiacales
    data1 += GetDetalleTicketByIdticket2(datos_ticket['id_ticket'])

    #definiendo Footer ticket
    data1 += "\\n"
    data1 += "-----------------------------"
    data1 += "\\n"
    data1 += "NUMEROS JUGADOS: "+str(resulta['numero_jugadas'])
    data1 += "\\n"
    data1 += "TOTAL: "+str(datos_ticket['total_ticket'])
    data1 += "\\n"
    data1 += "Caduca en "+str(parametros['tiempo_anulacion_ticket'])+" dias"

    # Parametros de Impresora
    ida_taquilla = GetIdTaquillabyNumero(id_taquilla)
    datos_impresora = GetDatosImpresora(str(ida_taquilla))

    lineas_saltar_despues=datos_impresora["lineas_saltar_despues"];
    ver_numeros_incompletos=datos_impresora["ver_numeros_incompletos"];
    ver_numeros_agotados=datos_impresora["ver_numeros_agotados"];

    #INCOMPLETOS Y AGOTADOS
    data1 += GetNumerosIncompletobyIdticket(datos_ticket['id_ticket'],ver_numeros_incompletos,ver_numeros_agotados)

    #Numeros de line feed definidos en parametros de impresora
    for id in range(lineas_saltar_despues):
        data1 += "\\n"        

    print data1

    #Enviando Segun Parametro de Impresora
    if tipo_impresora == 'P':
        print_parallel(data1)
    else:
        if tipo_impresora == 'S':
            print_serial(data1)
    

def GetLastTicket(id_taquilla):
    sql = "SELECT MAX(id_ticket) as id_ticket FROM ticket WHERE status='1' AND taquilla  = "+id_taquilla;
    #print sql
    cursor.execute(sql)
    results = cursor.fetchall()
    for row in results:
        id_ticket = row[0]
        #print id_ticket   
    sql = "SELECT id_ticket, serial, fecha_hora, total_ticket, id_usuario FROM ticket WHERE status='1' AND id_ticket  = "+id_ticket;
    cursor.execute(sql)
    results = cursor.fetchall()
    for row in results:
        id_ticket = row[0]
        serial = row[1]
        fecha_hora = row[2]
        total_ticket = row[3]
        id_usuario = row[4]
        #print id_ticket, serial, fecha_hora, total_ticket, id_usuario
        #$fecha_hora=$day."-".$month."-".$year." ".$hour.":".$minute;

        fecha_hora = fecha_hora.strftime("%d-%m-%Y %H:%M")
        #print fecha_hora
        id_usuario = str(id_usuario)
        nombre_usuario = GetNombreUsuarioById(id_usuario)

    #id_ticket, serial, fecha_hora, total_ticket, id_usuario, nombre_usuario
    datos_ticket =  {'id_ticket' : id_ticket, 'serial' : serial, 'fecha_hora' : fecha_hora, 'total_ticket' : total_ticket, 'id_usuario' : id_usuario, 'nombre_usuario' : nombre_usuario}   
    return datos_ticket

    
#obtiene los Numeros Incompleto y Agotados del ticket filtrando si zodiacal
def GetNumerosIncompletobyIdticket(id_ticket,ver_numeros_incompletos,ver_numeros_agotados):
    sql = "SELECT * FROM incompletos_agotados WHERE id_ticket = "+id_ticket
    sql+= " ORDER BY incompleto, numero, id_sorteo ASC";

    #print sql
    cursor.execute(sql)
    results = cursor.fetchall()

    data1=''
    id_bandera_actual=0
    for row in results:
        #obteniendo el valor incompleto row['incompleto'] / 1 o 2
        id_bandera = str(row[8])
        id_sorteo = str(row[4])
        id_zodiacal = str(row[6])
        numero = str(row[3])
        monto_restante = str(row[7])
       

        if id_bandera != id_bandera_actual:
            id_bandera_actual=id_bandera
            if id_bandera_actual == '1':
                if ver_numeros_incompletos== '1':
                    data1 += "\\n"
                    data1 += "\\n"
                    data1 += "\\n"				
                    data1 += "INCOMPLETOS";
            if id_bandera_actual == '2':
                if ver_numeros_agotados == '1':
                    data1 += "\\n"
                    data1 += "\\n"			
                    data1 += "AGOTADOS";

        nombre_sorteo = GetNombreSorteo(id_sorteo)
	data1 += "\\n"
        data1 += nombre_sorteo

        if id_zodiacal == '0':
            data1 += "\\n"
            data1 += numero+" FALTA "+monto_restante+"  "
            
        else:
            nombre_signo = GetPreNombreSigno(id_zodiacal)
            data1 += numero+" "+nombre_signo+" FALTA "+monto_restante+"  "

    return data1

#obtiene los detalles del ticket filtrando no zodiacal
def GetDetalleTicketByIdticket(id_ticket):
    sql = "SELECT * FROM detalle_ticket WHERE id_ticket = "+id_ticket
    sql+= " ORDER BY id_sorteo, id_zodiacal, numero ASC";

    cursor.execute(sql)

    numero_jugadas = cursor.rowcount

    results = cursor.fetchall()
 
    contador=0
    id_sorteo_actual=0
    data1 = ""
    for row in results:
        id_sorteo = str(row[3])
        id_zodiacal = str(row[5])
        numero = row[2]
        monto = str(row[7])
        
        if id_sorteo != id_sorteo_actual and id_zodiacal == '0':
            contador=0
            nombre_sorteo = GetNombreSorteo(id_sorteo)
            data1 += "\\n"
            data1 += nombre_sorteo
            
        if id_zodiacal == '0':
            if contador % 2:
                data1 += numero+" x "+monto+"  "
            else:
                data1 += "\\n"
                data1 += numero+" x "+monto+"  "
                

        contador += 1

    result = {'data1': data1, 'numero_jugadas':numero_jugadas}
    return result


#obtiene los detalles del ticket filtrando si zodiacal
def GetDetalleTicketByIdticket2(id_ticket):
    sql = "SELECT * FROM detalle_ticket WHERE id_ticket = "+id_ticket
    sql+= " ORDER BY numero, id_sorteo, id_zodiacal ASC";

    cursor.execute(sql)

    numero_jugadas = cursor.rowcount

    results = cursor.fetchall()
 
    contador=0
    id_sorteo_actual=0
    data1 = ""
    for row in results:
        id_sorteo = str(row[3])
        id_zodiacal = str(row[5])
        numero = row[2]
        monto = str(row[7])
        
        if id_sorteo != id_sorteo_actual and id_zodiacal != '0':
            contador=0
            nombre_sorteo = GetNombreSorteo(id_sorteo)
            data1 += "\\n"
            data1 += nombre_sorteo
            
        if id_zodiacal != '0':
            
            nombre_signo = GetPreNombreSigno(id_zodiacal)
            if contador % 2:
                data1 += numero+" "+nombre_signo+" x "+monto+"  "
            else:
                data1 += "\\n"
                data1 += numero+" "+nombre_signo+" x "+monto+"  "
                

        contador += 1
        
    return data1

def GetDatosImpresora(id_taquilla):
    sql = "SELECT lineas_saltar_despues, ver_numeros_incompletos, ver_numeros_agotados FROM impresora_taquillas WHERE id_taquilla = "+id_taquilla;
    #print sql
    cursor.execute(sql)
    row = cursor.fetchone()

    datos_impresora = {'lineas_saltar_despues':row[0], 'ver_numeros_incompletos':row[1], 'ver_numeros_agotados':row[2]}

    return datos_impresora

def GetIdTaquillabyNumero(id):
    #Preparacion del query
    sql = "SELECT id_taquilla FROM taquillas WHERE numero_taquilla = "+id
    cursor.execute(sql)
    row = cursor.fetchone()
    return row[0]

def GetPreNombreSigno(id):
    #Preparacion del query
    sql = "SELECT pre_zodiacal FROM zodiacal WHERE Id_zodiacal = "+id
    cursor.execute(sql)
    row = cursor.fetchone()
    return row[0]

def GetNombreSorteo(id):
    #Preparacion del query
    sql = "SELECT nombre_sorteo FROM sorteos WHERE status = 1 AND id_sorteo = "+id
    cursor.execute(sql)
    row = cursor.fetchone()
    return row[0]
    


def GetNombreUsuarioById(id_usuario):
    sql = "SELECT nombre_usuario FROM usuario WHERE id_usuario = "+id_usuario;
    cursor.execute(sql)
    results = cursor.fetchall()
    for row in results:
        nombre_usuario = row[0].split(' ')
        nombre_usuario = nombre_usuario[0]

    return nombre_usuario

def GetDatosParametros():
    sql = "SELECT * FROM parametros";
    cursor.execute(sql)
    results = cursor.fetchall()
    for row in results:
        nombre_agencia = row[2]
        tiempo_anulacion_ticket = row[4]
    parametros =  {'nombre_agencia' : nombre_agencia, 'tiempo_anulacion_ticket' : tiempo_anulacion_ticket}
    return parametros

def print_parallel(data1):
    try: 
        xs=open('LPT1','w')
        #xs.write(' \x1B\x77 ')
        #xs.write(data1)
        xs.write(str(data1))        
        xs.close()
        print "heyyyyy"
    except Exception:
        print "ocurrio"  

def print_serial(data1):
    puerto
    baudrate
    #ser = serial.Serial(port='COM1')
    #ser = serial.Serial('COM1', 9600)
    #ser = serial.Serial(puerto)
    ser = serial.Serial(puerto, baudrate)
    
    # Apertura del Puerto Serial
    """
    try:
        ser = serial.Serial(
            port = puerto,
            baudrate = 9600,
            parity = serial.PARITY_NONE,
            stopbits = serial.STOPBITS_ONE,
            bytesize = serial.EIGHTBITS)
            self.comSerial.open()
    except:
        ser = 0
    """    
    try: 

        ser.open()

    except Exception, e:

        print "error open serial port: " + str(e)

        exit()

    print ser.portstr       # check which port was really used
    #ser.write(data1) # write a string
    ser.write(str(data1))
    ser.close()    
    
if __name__ == "__main__":
    HOST, PORT = "", 9999

    # Create the server, binding to localhost on port 9999
    server = SocketServer.TCPServer((HOST, PORT), MyTCPHandler)
    # Activate the server; this will keep running until you
    # interrupt the program with Ctrl-C
    server.serve_forever()
