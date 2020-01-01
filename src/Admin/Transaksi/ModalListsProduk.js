import React, { Component } from 'react'
import { Modal, Table, Form } from 'react-bootstrap'

export default class ModalListsProduk extends Component {
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

   render() {
      return (
         <Modal show={this.props.openModalListsProduk} onHide={() => this.props._updateState({ name: 'openModalListsProduk', value: false })} size="lg">
            <Modal.Header closeButton>
               <Modal.Title>Daftar Produk</Modal.Title>
            </Modal.Header>
            <Modal.Body>
               <Table striped bordered hover size="sm" className="modal-lists-produk">
                  <thead>
                     <tr>
                        <th colSpan={5}>
                           <Form.Control name="cariProduk" value={this.props.cariProduk} onChange={this._onChange} size="sm" placeholder="Ketikkan kode/nama produk..." />
                        </th>
                     </tr>
                     <tr>
                        <th style={{ textAlign: 'center' }}>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Satuan</th>
                     </tr>
                  </thead>
                  <tbody>
                     {this.props.listsProduk.length > 0 ? this.props.listsProduk.map((data, key) => {
                        return (
                           <tr key={key} onClick={() => this.props._updateState({
                              name: 'setProduk',
                              value: data.id + '|' + data.kode + '|' + data.harga
                           })}>
                              <td align="center">{key + 1}</td>
                              <td>{data.kode}</td>
                              <td>{data.nama}</td>
                              <td>{data.harga.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
                              <td>{data.satuan}</td>
                           </tr>
                        )
                     }) : <tr>
                           <td colSpan={5}>Tidak ada produk tersedia.</td>
                        </tr>}
                  </tbody>
               </Table>
            </Modal.Body>
         </Modal>
      )
   }
}
