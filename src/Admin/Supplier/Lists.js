import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Button, Table } from 'react-bootstrap'
import axios from 'axios'
import MsgResponse from '../../MsgResponse'

axios.defaults.baseURL = siteURL
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

class Lists extends Component {
   constructor() {
      super()

      this.state = {
         status: true,
         msg_response: ''
      }
   }

   componentDidMount() {
      this.loadData = $('#datatable').DataTable({
         responsive: true,
         ordering: true,
         processing: true,
         serverSide: true,
         ajax: {
            url: siteURL + '/admin/supplier/getData',
            type: 'POST'
         },
         createdRow: (row) => {
            var rows = row.cells[0].children[1].children
            var _edit = rows[0].children[0]
            var _delete = rows[1].children[0]
      
            _edit.onclick = () => {
               open(siteURL + '/admin/supplier/edit/' + _edit.dataset.id, '_parent')
            }
      
            _delete.onclick = () => {
               if (confirm('Apakah anda yakin ingin menghapus?')) {
                  this._delete(_delete.dataset.id)
               }
            }
         },
         columns: [
            null,
            null,
            null,
            null
         ]
      });
   }

   _delete(id) {
      this.setState({ msg_response: 'Loading...' })
      var formData = new FormData()
      formData.append('id', id)
      formData.append('pageType', 'delete')
      
      axios.
         post('/admin/supplier/delete', formData).
         then(res => {
            var response = res.data
            this.setState({ ...response })

            if (response.status) {
               this.loadData.ajax.reload(null, false)
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
               <Col md={9} className="align-self-center">
                  <h4 className="text-themecolor m-b-0 m-t-0">Supplier</h4>
                  <Breadcrumb>
                     <Breadcrumb.Item active>Home</Breadcrumb.Item>
                     <Breadcrumb.Item active>Supplier</Breadcrumb.Item>
                  </Breadcrumb>
               </Col>
               <Col md={3} className="align-self-center">
                  <Button
                     variant="success"
                     className="float-right waves-effect waves-light"
                     size="sm"
                     onClick={() => open(siteURL + '/admin/supplier/tambah', '_parent')}
                  >Tambah</Button>
               </Col>
            </Row>
            <Row>
               <Col md={12}>
                  <MsgResponse {...this.state} />
                  <div className="card">
                     <Table striped bordered hover size="sm" id="datatable">
                        <thead>
                           <tr>
                              <th>Nama</th>
                              <th>Email/Telepon</th>
                              <th>Alamat</th>
                              <th>Keterangan</th>
                           </tr>
                        </thead>
                     </Table>
                  </div>
               </Col>
            </Row>
         </Container>
      )
   }
}

ReactDOM.render(<Lists />, document.getElementById('root'))