<?php
class comprobanteGeneral {
    public $ambiente; // string
    public $claveAcc; // string
    public $codDoc; // string
    public $configAplicacion; // configAplicacion
    public $configCorreo; // configCorreo
    public $contribuyenteEspecial; // string
    public $dirEstablecimiento; // string
    public $dirMatriz; // string
    public $establecimiento; // string
    public $fechaEmision; // string
    public $nombreComercial; // string
    public $obligadoContabilidad; // string
    public $ptoEmision; // string
    public $razonSocial; // string
    public $ruc; // string
    public $secuencial; // string
    public $tipoDoc; // string
    public $tipoEmision; // string
	public $padronMicroempresa;
    public $padronAgenteRetencion;
    public $numeroResolucion;
	public $artesanoCalificado;
}

class factura extends comprobanteGeneral {
	public $razonSocialComprador; // string
    public $tipoIdentificacionComprador; // string
	public $identificacionComprador; // string
	public $direccionComprador;//direccion de l comprador
    public $totalConImpuesto; // totalImpuesto
    public $totalDescuento; // string
    public $totalSinImpuestos; // string
	public $moneda; // string
    public $propina; // string
	public $guiaRemision; // string
    public $importeTotal; // string
	public $pagos;
    public $infoAdicional; // campoAdicional
    public $detalles; // detalleFactura
}

class detalleFactura {
    public $cantidad; // string
    public $codigoAuxiliar; // string
    public $codigoPrincipal; // string
    public $descripcion; // string
    public $descuento; // string
    public $detalleAdicional; // detalleAdicional
    public $impuestos; // impuesto
    public $precioTotalSinImpuesto; // string
    public $precioUnitario; // string
}

class liquidacionCompra extends comprobanteGeneral {
    public $detalles; // detalleLiquidacionCompra
    public $direccionProveedor; // string
    public $identificacionProveedor; // string
    public $importeTotal; // string
    public $infoAdicional; // campoAdicional
    public $moneda; // string
    public $razonSocialProveedor; // string
    public $tipoIdentificacionProveedor; // string
    public $totalConImpuesto; // totalImpuesto
    public $totalDescuento; // string
    public $totalSinImpuestos; // string
    public $pagos;
}

class detalleLiquidacionCompra {
    public $cantidad; // string
    public $codigoAuxiliar; // string
    public $codigoPrincipal; // string
    public $descripcion; // string
    public $descuento; // string
    public $detalleAdicional; // detalleAdicional
    public $impuestos; // impuesto
    public $precioTotalSinImpuesto; // string
    public $precioUnitario; // string
}

class guiaRemision extends comprobanteGeneral{
    public $destinatarios; // destinatario
    public $dirPartida; // string
    public $fechaFinTransporte; // string
    public $fechaIniTransporte; // string
    public $infoAdicional; // campoAdicional
    public $placa; // string
    public $razonSocialTransportista; // string
    public $rise; // string
    public $rucTransportista; // string
    public $tipoIdentificacionTransportista; // string
}

class destinatario {
    public $codDocSustento; // string
    public $codEstabDestino; // string
    public $detalles; // detalleGuiaRemision
    public $dirDestinatario; // string
    public $docAduaneroUnico; // string
    public $fechaEmisionDocSustento; // string
    public $identificacionDestinatario; // string
    public $motivoTraslado; // string
    public $numAutDocSustento; // string
    public $numDocSustento; // string
    public $razonSocialDestinatario; // string
    public $ruta; // string
}

class detalleGuiaRemision {
    public $cantidad; // string
    public $codigoAdicional; // string
    public $codigoInterno; // string
    public $descripcion; // string
    public $detallesAdicionales; // detalleAdicional
}

class comprobanteRetencion extends comprobanteGeneral {
    public $identificacionSujetoRetenido; // string
    public $impuestos; // impuestoComprobanteRetencion
    public $infoAdicional; // campoAdicional
    public $periodoFiscal; // string
    public $razonSocialSujetoRetenido; // string
    public $tipoIdentificacionSujetoRetenido; // string
}

class impuestoComprobanteRetencion {
    public $baseImponible; // string
    public $codDocSustento; // string
    public $codigo; // string
    public $codigoRetencion; // string
    public $fechaEmisionDocSustento; // string
    public $numDocSustento; // string
    public $porcentajeRetener; // string
    public $valorRetenido; // string
}

class notaCredito extends comprobanteGeneral {
    public $codDocModificado; // string
    public $detalles; // detalleNotaCredito
    public $fechaEmisionDocSustento; // string
    public $identificacionComprador; // string
    public $infoAdicional; // campoAdicional
    public $moneda; // string
    public $motivo; // string
    public $numDocModificado; // string
    public $razonSocialComprador; // string
    public $rise; // string
    public $tipoIdentificacionComprador; // string
    public $totalConImpuesto; // totalImpuesto
    public $totalSinImpuestos; // string
    public $valorModificacion; // string
}

class detalleNotaCredito {
    public $cantidad; // string
    public $codigoAdicional; // string
    public $codigoInterno; // string
    public $descripcion; // string
    public $descuento; // string
    public $detallesAdicionales; // detalleAdicional
    public $impuestos; // impuesto
    public $precioTotalSinImpuesto; // string
    public $precioUnitario; // string
}

class notaDebito extends comprobanteGeneral {
    public $codDocModificado; // string
    public $fechaEmisionDocSustento; // string
    public $identificacionComprador; // string
    public $impuestos; // impuesto
    public $infoAdicional; // campoAdicional
    public $motivos; // motivo
    public $numDocModificado; // string
    public $razonSocialComprador; // string
    public $rise; // string
    public $tipoIdentificacionComprador; // string
    public $totalSinImpuestos; // string
    public $valorTotal; // string
	public $pagos;
}

class motivo {
    public $razon; // string
    public $valor; // string
}

class pagos {
    public $formaPago;
    public $total;
    public $plazo;
    public $unidadTiempo;
}


class detalleAdicional {
    public $nombre; // string
    public $valor; // string
}

class campoAdicional {
    public $nombre; // string
    public $valor; // string
}

class impuesto {
    public $baseImponible; // string
    public $codigo; // string
    public $codigoPorcentaje; // string
    public $tarifa; // string
    public $valor; // string
}

class totalImpuesto {
    public $baseImponible; // string
    public $codigo; // string
    public $codigoPorcentaje; // string
    public $descuentoAdicional; // string
    public $tarifa; // string
    public $valor; // string
}

class configAplicacion {
    public $dirAutorizados; // string
    public $dirFirma; // string
    public $dirLogo; // string
    public $passFirma; // string
	public $dirIreport;//string
}

class configCorreo {
    public $correoAsunto; // string
    public $correoHost; // string
    public $correoPass; // string
    public $correoPort; // string
    public $correoRemitente; // string
    public $sslHabilitado; // boolean
	public $rutaLogo;
}

class comprobantePendiente {
    public $ambiente; // string
    public $codDoc; // string
    public $configAplicacion; // configAplicacion
    public $configCorreo; // configCorreo
    public $establecimiento; // string
    public $fechaEmision; // string
    public $ptoEmision; // string
    public $ruc; // string
    public $secuencial; // string
    public $tipoEmision; // string
    public $clavAcc;
	public $padronMicroempresa;
	public $padronAgenteRetencion;
	public $artesanoCalificado;
}

class procesarComprobanteLote {
    public $comprobanteLote; // comprobanteLote
}

class comprobanteLote {
    public $ambiente; // string
    public $claveAcceso; // string
    public $codDoc; // string
    public $comprobantes; // comprobanteGeneral
    public $configAplicacion; // configAplicacion
    public $configCorreo; // configCorreo
    public $establecimiento; // string
    public $fechaEmision; // string
    public $idUnico; // string
    public $ptoEmision; // string
    public $ruc; // string
    public $secuencial; // string
    public $tipoEmision; // string
	public $padronMicroempresa;
	public $padronAgenteRetencion;
	public $artesanoCalificado;
}

class procesarComprobanteLoteResponse {
    public $return; // respuestaComprobanteLote
}

class respuestaComprobanteLote {
    public $claveAccesoConsultada; // string
    public $error; // boolean
    public $mensajeGeneral; // mensajeGenerado
    public $respuestas; // respuesta
}

class mensajeGenerado {
    public $identificador; // string
    public $informacionAdicional; // string
    public $mensaje; // string
    public $tipo; // string
}

class respuesta {
    public $claveAcceso; // string
    public $comprobanteID; // string
    public $estadoComprobante; // string
    public $mensajes; // mensajeGenerado
    public $numeroAutorizacion; // string
    public $fechaAutorizacion;
}

class procesarComprobantePendiente {
    public $comprobantePendiente; // comprobantePendiente
}

class procesarComprobantePendienteResponse {
    public $return; // respuesta
}

class procesarComprobante {
    public $comprobante; // comprobanteGeneral
    public $envioSRI;
}

class procesarComprobanteResponse {
    public $return; // respuesta
}

class obtenerComprobante{
    public $claveAcceso;
    public $ambiente;
}
/**
 * ProcesarComprobanteElectronico class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class ProcesarComprobanteElectronico extends SoapClient {
    private static $classmap = array(
        'factura' => 'factura',
        'liquidacionCompra' => 'liquidacionCompra',
        'comprobanteGeneral' => 'comprobanteGeneral',
        'detalleFactura' => 'detalleFactura',
        'detalleLiquidacionCompra' => 'detalleLiquidacionCompra',
        'detalleAdicional' => 'detalleAdicional',
        'impuesto' => 'impuesto',
        'campoAdicional' => 'campoAdicional',
        'totalImpuesto' => 'totalImpuesto',
        'configAplicacion' => 'configAplicacion',
        'configCorreo' => 'configCorreo',
        'guiaRemision' => 'guiaRemision',
        'destinatario' => 'destinatario',
        'detalleGuiaRemision' => 'detalleGuiaRemision',
        'comprobanteRetencion' => 'comprobanteRetencion',
        'impuestoComprobanteRetencion' => 'impuestoComprobanteRetencion',
        'notaDebito' => 'notaDebito',
        'motivo' => 'motivo',
        'pagos' => 'pagos',
        'notaCredito' => 'notaCredito',
        'detalleNotaCredito' => 'detalleNotaCredito',
        'comprobantePendiente' => 'comprobantePendiente',
        'procesarComprobanteLote' => 'procesarComprobanteLote',
        'comprobanteLote' => 'comprobanteLote',
        'procesarComprobanteLoteResponse' => 'procesarComprobanteLoteResponse',
        'respuestaComprobanteLote' => 'respuestaComprobanteLote',
        'mensajeGenerado' => 'mensajeGenerado',
        'respuesta' => 'respuesta',
        'procesarComprobanteClaveContingencia' => 'procesarComprobanteClaveContingencia',
        'procesarComprobanteClaveContingenciaResponse' => 'procesarComprobanteClaveContingenciaResponse',
        'procesarComprobantePendiente' => 'procesarComprobantePendiente',
        'procesarComprobantePendienteResponse' => 'procesarComprobantePendienteResponse',
        'procesarComprobante' => 'procesarComprobante',
        'procesarComprobanteResponse' => 'procesarComprobanteResponse',
    );

	public function ProcesarComprobanteElectronico($wsdl = "http://localhost:8085/MasterOffline/ProcesarComprobanteElectronico?wsdl", $options = array()) {
        foreach (self::$classmap as $key => $value) {
            if (!isset($options['classmap'][$key])) {
                $options['classmap'][$key] = $value;
            }
        }
        parent::__construct($wsdl, $options);
    }

    /**
     *  
     *
     * @param procesarComprobantePendiente $parameters
     * @return procesarComprobantePendienteResponse
     */
    public function procesarComprobantePendiente(procesarComprobantePendiente $parameters) {
        return $this->__soapCall('procesarComprobantePendiente', array($parameters), array(
                    'uri' => 'http://Servicio/',
                    'soapaction' => ''
                        )
        );
    }

    /**
     *  
     *
     * @param procesarComprobanteClaveContingencia $parameters
     * @return procesarComprobanteClaveContingenciaResponse
    
    public function procesarComprobanteClaveContingencia(procesarComprobanteClaveContingencia $parameters) {
        return $this->__soapCall('procesarComprobanteClaveContingencia', array($parameters), array(
                    'uri' => 'http://Servicio/',
                    'soapaction' => ''
                        )
        );
    }
     */
    /**
     *  
     *
     * @param procesarComprobanteLote $parameters
     * @return procesarComprobanteLoteResponse
     */
    public function procesarComprobanteLote(procesarComprobanteLote $parameters) {
        return $this->__soapCall('procesarComprobanteLote', array($parameters), array(
                    'uri' => 'http://Servicio/',
                    'soapaction' => ''
                        )
        );
    }

    /**
     *  
     *
     * @param procesarComprobante $parameters
     * @return procesarComprobanteResponse
     */
    public function procesarComprobante(procesarComprobante $parameters) {
        return $this->__soapCall('procesarComprobante', array($parameters), array(
                    'uri' => 'http://Servicio/',
                    'soapaction' => ''
                        )
        );
    }
	
	public function obtenerComprobante(obtenerComprobante $parameters){
        return $this->__soapCall('obtenerComprobante',array($parameters),array(
                                                                            'uri' => 'http://Servicio/',
                                                                            'soapaction' => ''            
                                                                        )

        );
    }

}

?>