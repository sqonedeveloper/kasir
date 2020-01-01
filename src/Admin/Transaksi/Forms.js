import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Form, Table, InputGroup, Button } from 'react-bootstrap'
import axios from 'axios'
import ModalListsProduk from './ModalListsProduk'
import MsgResponse from '../../MsgResponse'
import ModalBayar from './ModalBayar'
import { rupiah, replaceDotWithEmpty, toNumeric } from '../../Helpers'
import ModalEditDaftarPesanan from './ModalEditDaftarPesanan'

axios.defaults.baseURL = siteURL
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

class Forms extends Component {
   constructor() {
      super()

      this.state = {
         btnLoading: false,
         errors: {},
         status: false,
         msg_response: '',
         id_transaksi: '',
         nota: '',
         kode: '',
         jumlah: '1',
         id_produk: '',
         harga: 0,
         grand_total: 0,
         openModalListsProduk: false,
         listsProduk: [],
         cariProduk: '',
         daftarPesanan: [],
         modalBayar: false,
         tgl_transaksi: '',
         grand_total_bayar: 0,
         uang_kembalian: 0,
         diskon_pembayaran: 0,
         jumlah_uang: 0,
         status_bayar: '0',
         modalEditDaftarPesanan: false,
         id_transaksi_detail: '',
         nama_transaksi_detail: '',
         harga_transaksi_detail: '',
         jumlah_transaksi_detail: ''
      }

      this._onChange = this._onChange.bind(this)
   }

   componentDidMount() {
      this.setState({
         ...content.detail,
         daftarPesanan: content.daftarPesanan.results,
         grand_total: content.daftarPesanan.grand_total,
         grand_total_bayar: content.daftarPesanan.grand_total,
         tgl_transaksi: content.tgl_transaksi
      })
   }

   _handleFocus(e) {
      e.target.select()
   }

   _onChange(e) {
      this.setState({ [e.target.name]: e.target.value })

      if (e.target.name === 'kode') {
         this._getIDProduk(e.target.value)
      }
   }

   _handleKeyPress(e) {
      if (e.key === 'Enter') {
         this._submit({
            kode: this.state.kode,
            id_produk: this.state.id_produk,
            harga: this.state.harga
         })
      }
   }

   _updateState(e) {
      this.setState({ [e.name]: e.value })

      if (e.name === 'setProduk') {
         var split_value = e.value.split('|'),
            id_produk = split_value[0],
            kode_produk = split_value[1],
            harga_produk = split_value[2]

         this.setState({
            id_produk: id_produk,
            kode: kode_produk,
            harga: harga_produk,
            openModalListsProduk: false
         })

         this._submit({
            kode: kode_produk,
            id_produk: id_produk,
            harga: harga_produk
         })
      } else if (e.name === 'cariProduk') {
         this._getListsProduk(e.value)
      } else if (e.name === 'diskon_pembayaran') {
         var diskon_bayar = toNumeric(replaceDotWithEmpty(e.value))
         var grand_total = toNumeric(replaceDotWithEmpty(this.state.grand_total))

         if (diskon_bayar.toString() !== 'NaN') {
            this.setState({
               diskon_pembayaran: rupiah(e.value),
               grand_total_bayar: grand_total - diskon_bayar
            })
         } else {
            this.setState({ grand_total_bayar: grand_total })
         }
      } else if (e.name === 'jumlah_uang') {
         var jumlah_uang = replaceDotWithEmpty(e.value)
         var grand_total_bayar = toNumeric(this.state.grand_total_bayar)

         this.setState({
            jumlah_uang: rupiah(e.value),
            uang_kembalian: jumlah_uang >= grand_total_bayar ? jumlah_uang - grand_total_bayar : 0
         })
      } else if (e.name === 'submitBayar' && e.value) {
         this._submitBayar()
      } else if (e.name === 'submitBayarCetak' && e.value) {
         this._submitBayar(true)
      }
   }

   _handleOpenModal() {
      this.setState({ openModalListsProduk: true })
      this._getListsProduk()
   }

   _getListsProduk(slug = 'all') {
      axios.
         get('/admin/transaksi/getListsProduk?query=' + slug).
         then(res => {
            var response = res.data
            this.setState({ ...response })
         }).
         catch(error => {
            console.log('Error', error.message)
         })
   }

   _getIDProduk(kode) {
      axios.
         get('/admin/transaksi/getIDProduk?kode=' + kode).
         then(res => {
            var response = res.data
            this.setState({ ...response })

            if (response.status) {
               this._submit({
                  kode: response.kode,
                  id_produk: response.id_produk,
                  harga: response.harga
               })
            }
         }).
         catch(error => {
            console.log('Error', error.message)
         })
   }

   _submit(params = []) {
      this.setState({ btnLoading: true })
      var formData = new FormData()
      formData.append('pageType', 'tambahPesanan')
      formData.append('id_transaksi', this.state.id_transaksi)
      formData.append('nota', this.state.nota)
      formData.append('kode', params.kode)
      formData.append('id_produk', params.id_produk)
      formData.append('jumlah', this.state.jumlah)
      formData.append('harga', params.harga)

      axios.
         post('/admin/transaksi/submit', formData).
         then(res => {
            var response = res.data
            this.setState({
               status: response.status,
               errors: response.errors,
               msg_response: response.msg_response
            })

            if (response.status) {
               this.setState({
                  daftarPesanan: response.daftarPesanan.results,
                  grand_total: response.daftarPesanan.grand_total,
                  grand_total_bayar: response.daftarPesanan.grand_total
               })
            }
         }).
         catch(error => {
            console.log('Error', error.message)
         }).
         finally(() => {
            this.setState({ btnLoading: false })
         })
   }

   _submitBayar(cetak = false) {
      this.setState({ btnLoading: true })
      var formData = new FormData()
      formData.append('pageType', 'bayarTransaksi')
      formData.append('id_transaksi', this.state.id_transaksi)
      formData.append('tanggal', this.state.tgl_transaksi)
      formData.append('diskon', this.state.diskon_pembayaran)
      formData.append('total_bayar', this.state.grand_total_bayar)
      formData.append('grand_total', this.state.grand_total)
      formData.append('jumlah_uang', this.state.jumlah_uang)

      axios.
         post('/admin/transaksi/submitBayar', formData).
         then(res => {
            var response = res.data
            this.setState({ ...response })

            if (response.status) {
               if (cetak) {
                  open(siteURL + '/admin/transaksi/cetak/' + this.state.id_transaksi, '_blank')
                  window.reload()
               } else {
                  open(siteURL + '/admin/transaksi', '_parent')
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

   _deleteDaftarPesanan(id) {
      this.setState({
         status: true,
         msg_response: 'Loading...'
      })

      var formData = new FormData()
      formData.append('pageType', 'deleteDaftarPesanan')
      formData.append('id', id)
      formData.append('id_transaksi', this.state.id_transaksi)
      
      axios.
         post('/admin/transaksi/deleteDaftarPesanan', formData).
         then(res => {
            var response = res.data
            this.setState({ ...response })

            if (response.status) {
               this.setState({
                  daftarPesanan: response.daftarPesanan.results,
                  grand_total: response.daftarPesanan.grand_total,
                  grand_total_bayar: response.daftarPesanan.grand_total
               })
            }
         }).
         catch(error => {
            console.log('Error', error.message)
         })
   }

   render() {
      return (
         <Container fluid={true}>
            <Row className="page-titles">
               <Col md={12} className="align-self-center">
                  <h4 className="text-themecolor m-b-0 m-t-0">Transaksi</h4>
                  <Breadcrumb>
                     <Breadcrumb.Item active>Home</Breadcrumb.Item>
                     <Breadcrumb.Item active>{document.getElementsByTagName('title')[0].innerText}</Breadcrumb.Item>
                  </Breadcrumb>
               </Col>
            </Row>
            <Row>
               <Col md={12}>
                  <MsgResponse {...this.state} />
                  <div className="card">
                     <div className="card-body">
                        <Row>
                           <Col md={6}>
                              {this.state.status_bayar === '0' ? <>
                                 <Form.Group as={Row} className={this.state.errors.kode ? 'has-danger' : ''}>
                                    <Form.Label column md={3}>Barcode/Kode</Form.Label>
                                    <Col md={9}>
                                       <InputGroup size="sm">
                                          <Form.Control ref={e => this.kode = e} onFocus={this._handleFocus.bind(this)} name="kode" value={this.state.kode} onChange={this._onChange} size="sm" autoFocus onKeyPress={this._handleKeyPress.bind(this)} />
                                          <InputGroup.Prepend>
                                             <InputGroup.Text
                                                style={{ cursor: 'pointer' }}
                                                onClick={this._handleOpenModal.bind(this)}
                                             ><i className="mdi mdi-refresh" /></InputGroup.Text>
                                          </InputGroup.Prepend>
                                       </InputGroup>
                                       <Form.Control.Feedback type="invalid">{this.state.errors.kode}</Form.Control.Feedback>
                                    </Col>
                                 </Form.Group>
                                 <Form.Group as={Row} className={this.state.errors.jumlah ? 'has-danger' : ''}>
                                    <Form.Label column md={3}>Jumlah</Form.Label>
                                    <Col md={9}>
                                       <Form.Control name="jumlah" value={this.state.jumlah} onChange={this._onChange} size="sm" onKeyPress={this._handleKeyPress.bind(this)} />
                                       <Form.Control.Feedback type="invalid">{this.state.errors.jumlah}</Form.Control.Feedback>
                                    </Col>
                                 </Form.Group>
                                 <Col md={{ span: 9, offset: 3 }}>
                                    {this.state.daftarPesanan.length > 0 ? <div className="btn-group">
                                       <Button
                                          variant="success"
                                          className="waves-effect waves-light"
                                          size="sm"
                                          onClick={() => this.setState({ modalBayar: true })}
                                       >Bayar</Button>
                                       <Button
                                          variant="info"
                                          className="waves-effect waves-light"
                                          size="sm"
                                          onClick={() => open(siteURL + '/admin/transaksi/insertTransaksi', '_parent')}
                                       >Transaksi Baru</Button>
                                    </div> : ''}
                                 </Col>
                              </> : ''}
                           </Col>
                           <Col md={6}>
                              <p className="float-right">
                                 Nota : <strong style={{ fontWeight: 500 }}>{this.state.nota}</strong>
                                 <br />
                                 <span style={{ fontSize: 30, fontWeight: 500 }}>{rupiah(this.state.grand_total)}</span>
                              </p>
                           </Col>
                        </Row>
                     </div>
                  </div>
                  <div className="card">
                     <Table striped bordered hover size="sm" id="datatable">
                        <thead>
                           <tr>
                              <th style={{ textAlign: 'center' }}>No</th>
                              <th>Nama</th>
                              <th style={{ textAlign: 'right' }}>Harga</th>
                              <th style={{ textAlign: 'center' }}>Jumlah</th>
                              <th style={{ textAlign: 'right' }}>Diskon</th>
                              <th style={{ textAlign: 'right' }}>Total</th>
                           </tr>
                        </thead>
                        <tbody>
                           {this.state.daftarPesanan.length > 0 ? this.state.daftarPesanan.map((data, key) => {
                              return (
                                 <tr key={key}>
                                    <td align="center">{key + 1}</td>
                                    <td>
                                       {data.nama}
                                       {content.detail.status_bayar !== '1' ? <div className="row-actions">
                                          <span className="edit">
                                             <a onClick={() => this.setState({
                                                id_transaksi_detail: data.id,
                                                modalEditDaftarPesanan: true,
                                                nama_transaksi_detail: data.nama,
                                                harga_transaksi_detail: rupiah(data.harga),
                                                jumlah_transaksi_detail: data.jumlah
                                             })}>Edit</a>
                                          </span>
                                          <span className="delete"><a onClick={this._deleteDaftarPesanan.bind(this, data.id)} data-type="delete">Delete</a></span>
                                       </div> : ''}
                                    </td>
                                    <td align="right">{rupiah(data.harga)}</td>
                                    <td align="center">{data.jumlah}</td>
                                    <td align="right">{rupiah(data.diskon)}</td>
                                    <td align="right">{rupiah(data.total)}</td>
                                 </tr>
                              )
                           }) : <tr>
                              <td align="center" colSpan={6}>Belum ada pesanan.</td>
                           </tr>}
                        </tbody>
                     </Table>
                  </div>
               </Col>
            </Row>
            <ModalListsProduk {...this.state} _updateState={e => this._updateState(e)} />
            <ModalBayar {...this.state} _updateState={e => this._updateState(e)} />
            <ModalEditDaftarPesanan  {...this.state} _updateState={e => this._updateState(e)} />
         </Container>
      )
   }
}

ReactDOM.render(<Forms />, document.getElementById('root'))