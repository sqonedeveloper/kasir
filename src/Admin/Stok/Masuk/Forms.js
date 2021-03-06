import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Form, Button } from 'react-bootstrap'
import axios from 'axios'
import MsgResponse from '../../../MsgResponse'

axios.defaults.baseURL = siteURL
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

var Typeahead = require('react-bootstrap-typeahead').Typeahead

class Forms extends Component {
   constructor() {
      super()

      this.state = {
         btnLoading: false,
         errors: {},
         status: false,
         msg_response: '',
         tanggal: '',
         id_produk: '',
         kode: '',
         nama: '',
         detail: '1',
         id_supplier: '',
         detail_lainnya: '',
         stok: '',
         listsProduk: []
      }

      this._onChange = this._onChange.bind(this)
   }

   componentDidMount() {
      this.setState({ ...content })
   }

   _onChange(e) {
      this.setState({ [e.target.name]: e.target.value })
   }

   _submit() {
      this.setState({ btnLoading: true })
      var formData = new FormData()
      formData.append('pageType', pageType)
      formData.append('tanggal', this.state.tanggal)
      formData.append('id_produk', this.state.id_produk)
      formData.append('kode', this.state.kode)
      formData.append('nama', this.state.nama)
      formData.append('detail', this.state.detail)
      formData.append('id_supplier', this.state.id_supplier)
      formData.append('detail_lainnya', this.state.detail_lainnya)
      formData.append('stok', this.state.stok)

      axios.
         post('/admin/stok/masuk/submit', formData).
         then(res => {
            var response = res.data
            this.setState({ ...response })

            if (response.status) {
               if (pageType === 'insert') {
                  this.setState({ ...response.emptyPost })
               }
            }
         }).
         catch(error => {
            console.log('Error', error.message)
         }).
         finally(() => {
            this.setState({ btnLoading: false })
         })
   }

   render() {
      return (
         <Container fluid={true}>
            <Row className="page-titles">
               <Col md={12} className="align-self-center">
                  <h4 className="text-themecolor m-b-0 m-t-0">Stok</h4>
                  <Breadcrumb>
                     <Breadcrumb.Item active>Home</Breadcrumb.Item>
                     <Breadcrumb.Item active>Stok</Breadcrumb.Item>
                     <Breadcrumb.Item active>Masuk</Breadcrumb.Item>
                     <Breadcrumb.Item active>{document.getElementsByTagName('title')[0].innerText}</Breadcrumb.Item>
                  </Breadcrumb>
               </Col>
            </Row>
            <Row>
               <Col md={12}>
                  <MsgResponse {...this.state} />
                  <div className="card">
                     <div className="card-body">
                        <Form.Group as={Row} className={this.state.errors.tanggal ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Tanggal</Form.Label>
                           <Col md={9}>
                              <Form.Control name="tanggal" value={this.state.tanggal} onChange={this._onChange} size="sm" autoFocus type="date" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.tanggal}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.kode ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Barcode/Kode</Form.Label>
                           <Col md={9}>
                              <Typeahead
                                 bsSize="sm"
                                 placeholder="Ketikkan Barcode/kode disini..."
                                 options={this.state.listsProduk}
                                 onChange={e => {
                                    e.length > 0 ? this.setState({
                                       id_produk: e[0].value,
                                       kode: e[0].kode,
                                       nama: e[0].nama
                                    }) : e
                                 }}
                              />
                              <Form.Control.Feedback type="invalid">{this.state.errors.kode}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.nama ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Nama Produk</Form.Label>
                           <Col md={9}>
                              <Form.Control value={this.state.nama} size="sm" disabled={true} />
                              <Form.Control.Feedback type="invalid">{this.state.errors.nama}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.detail ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Detail</Form.Label>
                           <Col md={9}>
                              <label className="custom-control custom-radio">
                                 <input name="detail" value="1" onChange={this._onChange} type="radio" className="custom-control-input" checked={this.state.detail === '1' ? true : false} />
                                 <span className="custom-control-label">Penambahan Stok Produk</span>
                              </label>
                              <label className="custom-control custom-radio">
                                 <input name="detail" value="2" onChange={this._onChange} type="radio" className="custom-control-input" checked={this.state.detail === '2' ? true : false} />
                                 <span className="custom-control-label">Lainnya</span>
                              </label>
                              <Form.Control.Feedback type="invalid">{this.state.errors.detail}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        {this.state.detail === '1' ? <Form.Group as={Row} className={this.state.errors.id_supplier ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Supplier</Form.Label>
                           <Col md={9}>
                              <Form.Control name="id_supplier" value={this.state.id_supplier} onChange={this._onChange} size="sm" as="select">
                                 <option value="">--pilih--</option>
                                 {content.listsSupplier.map((data, key) => {
                                    return <option value={data.value} key={key}>{data.label}</option>
                                 })}
                              </Form.Control>
                              <Form.Control.Feedback type="invalid">{this.state.errors.id_supplier}</Form.Control.Feedback>
                           </Col>
                        </Form.Group> : ''}
                        {this.state.detail === '2' ? <Form.Group as={Row} className={this.state.errors.detail_lainnya ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Detail Lainnya</Form.Label>
                           <Col md={9}>
                              <Form.Control name="detail_lainnya" value={this.state.detail_lainnya} onChange={this._onChange} size="sm" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.detail_lainnya}</Form.Control.Feedback>
                           </Col>
                        </Form.Group> : ''}
                        <Form.Group as={Row} className={this.state.errors.stok ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Jumlah Stok</Form.Label>
                           <Col md={9}>
                              <Form.Control name="stok" value={this.state.stok} onChange={this._onChange} size="sm" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.stok}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Col md={{ offset: 3, span: 9 }}>
                           <Button
                              variant="success"
                              className="waves-effect waves-light"
                              size="sm"
                              onClick={this.state.btnLoading ? null : this._submit.bind(this)}
                           >{this.state.btnLoading ? 'Loading...' : 'Submit'}</Button>
                        </Col>
                     </div>
                  </div>
               </Col>
            </Row>
         </Container>
      )
   }
}

ReactDOM.render(<Forms />, document.getElementById('root'))