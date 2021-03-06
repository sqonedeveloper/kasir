import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Table, Button } from 'react-bootstrap'

class Lists extends Component {
   componentDidMount() {
      this.loadData = $('#datatable').DataTable({
         responsive: true,
         ordering: true,
         processing: true,
         serverSide: true,
         ajax: {
            url: siteURL + '/admin/produk/dataProduk/getData',
            type: 'POST'
         },
         createdRow: (row) => {
            var rows = row.cells[0].children[1].children
            var _edit = rows[0].children[0]
            var _delete = rows[1].children[0]

            _edit.onclick = () => {
               open(siteURL + '/admin/produk/dataProduk/edit/' + _edit.dataset.id, '_parent')
            }

            _delete.onclick = () => {
               if (confirm('Are you sure you want to delete?')) {
                  this._delete(_delete.dataset.id)
               }
            }
         },
         columns: [
            null,
            null,
            null,
            null,
            { orderable: false },
         ]
      });
   }

   render() {
      return (
         <Container fluid={true}>
            <Row className="page-titles">
               <Col md={9} className="align-self-center">
                  <h4 className="text-themecolor m-b-0 m-t-0">Produk</h4>
                  <Breadcrumb>
                     <Breadcrumb.Item active>Home</Breadcrumb.Item>
                     <Breadcrumb.Item active>Produk</Breadcrumb.Item>
                     <Breadcrumb.Item active>{document.getElementsByTagName('title')[0].innerText}</Breadcrumb.Item>
                  </Breadcrumb>
               </Col>
               <Col md={3} className="align-self-center">
                  <Button
                     variant="success"
                     className="waves-effect waves-light float-right"
                     size="sm"
                     onClick={() => open(siteURL + '/admin/produk/dataProduk/tambah', '_parent')}
                  >Tambah</Button>
               </Col>
            </Row>
            <Row>
               <Col md={12}>
                  <div className="card">
                     <Table striped bordered hover size="sm" id="datatable">
                        <thead>
                           <tr>
                              <th>Nama Produk</th>
                              <th>Harga</th>
                              <th>Satuan</th>
                              <th>Kategori</th>
                              <th>Stok</th>
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