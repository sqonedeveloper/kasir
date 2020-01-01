import React, { Component } from 'react'
import { Button, Modal, Col, Form, Row } from 'react-bootstrap'

export default class ModalEditDaftarPesanan extends Component {
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
         <Modal show={this.props.modalEditDaftarPesanan} onHide={() => this.props._updateState({ name: 'modalEditDaftarPesanan', value: false })} size="lg">
            <Modal.Header closeButton>
               <Modal.Title>Edit Pesanan</Modal.Title>
            </Modal.Header>
            <Modal.Body>
               <div className="p-10">
                  <Form.Group as={Row}>
                     <Form.Label column md={3}>Nama</Form.Label>
                     <Col md={9}>
                        <Form.Control value={this.props.nama_transaksi_detail} size="sm" disabled={true} />
                     </Col>
                  </Form.Group>
                  <Form.Group as={Row}>
                     <Form.Label column md={3}>Harga</Form.Label>
                     <Col md={9}>
                        <Form.Control value={this.props.harga_transaksi_detail} size="sm" disabled={true} />
                     </Col>
                  </Form.Group>
                  <Form.Group as={Row}>
                     <Form.Label column md={3}>Jumlah</Form.Label>
                     <Col md={9}>
                        <Form.Control name="jumlah_transaksi_detail" value={this.props.jumlah_transaksi_detail} onChange={this._onChange} size="sm" type="numeric" onFocus={this._handleFocus.bind(this)} />
                     </Col>
                  </Form.Group>
               </div>
            </Modal.Body>
            <Modal.Footer>
               <Button
                  variant="success"
                  className="waves-effect waves-light"
                  size="sm"
               >{this.props.btnLoading ? 'Loading...' : 'Update'}</Button>
            </Modal.Footer>
         </Modal>
      )
   }
}
