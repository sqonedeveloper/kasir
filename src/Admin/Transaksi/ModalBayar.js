import React, { Component } from 'react'
import { Modal, Row, Form, Col, Button } from 'react-bootstrap'
import { rupiah } from '../../Helpers'

export default class ModalBayar extends Component {
   constructor(props) {
      super(props)

      this._onChange = this._onChange.bind(this)
   }

   _onChange(e) {
      this.props._updateState({
         name: e.target.name,
         value: e.target.value
      })
   }

   _handleFocus(e) {
      e.target.select()
   }

   render() {
      return (
         <Modal show={this.props.modalBayar} onHide={() => this.props._updateState({ name: 'modalBayar', value: false })} size="lg">
            <Modal.Header closeButton>
               <Modal.Title>Bayar</Modal.Title>
            </Modal.Header>
            <Modal.Body>
               <Row className="p-10">
                  <Col md={6}>
                     <Form.Group as={Row} className={this.props.errors.tanggal ? 'has-danger' : ''}>
                        <Form.Label column md={4}>Tanggal</Form.Label>
                        <Col md={8}>
                           <Form.Control name="tgl_transaksi" value={this.props.tgl_transaksi} onChange={this._onChange} size="sm" type="date" />
                           <Form.Control.Feedback type="invalid">{this.props.errors.tanggal}</Form.Control.Feedback>
                        </Col>
                     </Form.Group>
                     <Form.Group as={Row}>
                        <Form.Label column md={4}>Nota</Form.Label>
                        <Col md={8}>
                           <Form.Control value={this.props.nota} size="sm" disabled={true} />
                        </Col>
                     </Form.Group>
                     <Form.Group as={Row} className={this.props.errors.jumlah_uang ? 'has-danger' : ''}>
                        <Form.Label column md={4}>Jumlah Bayar</Form.Label>
                        <Col md={8}>
                           <Form.Control name="jumlah_uang" value={this.props.jumlah_uang} onChange={this._onChange} size="sm" onFocus={this._handleFocus.bind(this)} />
                           <Form.Control.Feedback type="invalid">{this.props.errors.jumlah_uang}</Form.Control.Feedback>
                        </Col>
                     </Form.Group>
                  </Col>
                  <Col md={6}>
                     <Form.Group as={Row}>
                        <Form.Label column md={5}>Total Transaksi</Form.Label>
                        <Col md={7}>
                           <Form.Control value={rupiah(this.props.grand_total)} size="sm" disabled={true} />
                        </Col>
                     </Form.Group>
                     <Form.Group as={Row} className={this.props.errors.diskon ? 'has-danger' : ''}>
                        <Form.Label column md={5}>Diskon</Form.Label>
                        <Col md={7}>
                           <Form.Control name="diskon_pembayaran" onChange={this._onChange} value={this.props.diskon_pembayaran} size="sm" onFocus={this._handleFocus.bind(this)} />
                           <Form.Control.Feedback type="invalid">{this.props.errors.diskon}</Form.Control.Feedback>
                        </Col>
                     </Form.Group>
                  </Col>
               </Row>
               <Row>
                  <Col md={6}>
                     <Form.Group>
                        <p>
                           Kembalian Rp{' '}
                           <span style={{ fontSize: 35, fontWeight: 400 }}>
                              {rupiah(this.props.uang_kembalian)}
                           </span>
                        </p>
                     </Form.Group>
                  </Col>
                  <Col md={6}>
                     <Form.Group>
                        <p className="float-right">
                           Grand Total Rp{' '}
                           <span style={{ fontSize: 35, fontWeight: 400 }}>
                              {rupiah(this.props.grand_total_bayar)}
                           </span>
                        </p>
                     </Form.Group>
                  </Col>
               </Row>
            </Modal.Body>
            <Modal.Footer>
               <Button
                  variant="success"
                  className="waves-effect waves-light"
                  size="sm"
                  onClick={() => this.props._updateState({ name: 'submitBayar', value: true })}
               >{this.props.btnLoading ? 'Loading...' : 'Bayar'}</Button>
               <Button
                  variant="warning"
                  className="waves-effect waves-light"
                  size="sm"
                  onClick={() => this.props._updateState({ name: 'submitBayarCetak', value: true })}
               >{this.props.btnLoading ? 'Loading...' : 'Bayar dan Cetak'}</Button>
            </Modal.Footer>
         </Modal>
      )
   }
}