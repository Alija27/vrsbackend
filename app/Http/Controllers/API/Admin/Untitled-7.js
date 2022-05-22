import React from "react";
import { Link } from "react-router-dom";
import { useEffect, useState } from "react";
import Swal from "sweetalert2";

import axios from "axios";
const BookingIndex = () => {
  const [bookings, setBookings] = useState([]);
  const [loading, setLoading] = useState(false);
  const [changeStatusMsg, setChangeStatusMsg] = useState("");
  const [bookingStatus, setBookingStatus] = useState("");
  const token = localStorage.getItem("token");

  const fetchBookings = async () => {
    setLoading(true);
    await axios
      .get("http://localhost:8000/api/viewbookings", {
        headers: {
          Authorization: "Bearer " + token,
        },
      })
      .then((res) => {
        setBookings(res.data);
        console.log(res.data);
      })
      .catch((err) => {
        console.log(err.response.data.msg);
      });
    setLoading(false);
  };

  // const handleStatusChange = (e) => {
  //   setBookingStatus({ ...bookingStatus, [e.target.name]: e.target.value });
  // };

  const updateStatus = async (id, status) => {
    await axios
      .put(
        `http://localhost:8000/api/changestatus/${id}`,
        {
          status: status,
        },
        {
          headers: {
            Authorization: "Bearer " + token,
          },
        }
      )
      .then((res) => {
        // setChangeStatusMsg(res.data.message);
        Swal.fire({
          icon: "success",
          text: res.data.message,
        });
      });
  };

  useEffect(() => {
    fetchBookings();
  }, []);

  const handleDelete = async (id) => {
    const isConfirmed = await Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
    }).then((res) => {
      return res.isConfirmed;
    });

    if (isConfirmed) {
      await axios
        .delete(`http://localhost:8000/api/bookings/${id}`, {
          headers: {
            Authorization: "Bearer " + token,
          },
        })
        .then((res) => {
          Swal.fire({
            icon: "success",
            text: res.data.message,
          });
          fetchBookings();
        })
        .catch((err) => {
          if (err.response.status === 500) {
            Swal.fire({
              text: "Status " + err.response.status + ": Something went wrong!",
              icon: "error",
            });
          } else {
            Swal.fire({
              text: "Status " + err.response.status + ": Something went wrong!",
              icon: "error",
            });
          }
        });
    }
  };

  return (
    <div>
      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-header">
              <div className="card-title text-lg">Bookings</div>
              <div className="card-tools">
                <Link to="/admin/bookings/create" className="btn-sm bg-indigo">
                  <i className="fas fa-plus-circle mr-1"></i> Add New
                </Link>
              </div>
            </div>
            <div className="card-body p-0" style={{ overflowX: "auto" }}>
              <table className="table table-hover table-bordered">
                <thead className="bg-indigo">
                  <tr className="text-center">
                    <th>SN</th>
                    <th>Customers</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Room Type</th>
                    <th>Room</th>
                    <th>Price</th>
                    <th>Status</th>
                    {/* <th>Actions</th> */}
                  </tr>
                </thead>
                <tbody>
                  {loading ? (
                    <tr>
                      <td colSpan={9}>
                        <div className="d-flex justify-content-center py-5">
                          <div
                            className="spinner-border text-indigo"
                            role="status"
                          >
                            <span className="sr-only">Loading...</span>
                          </div>
                        </div>
                      </td>
                    </tr>
                  ) : (
                    ""
                  )}
                  {bookings.map((booking, index) => {
                    return (
                      <tr>
                        <td>{index + 1}</td>
                        <td>{booking.user_id}</td>
                        <td>{booking.start_date}</td>
                        <td>{booking.end_date}</td>
                        <td>{booking.roomtype_id}</td>
                        <td>{booking.room_id}</td>
                        <td>{booking.price}</td>
                        <td>
                          <form>
                            <select
                              className="form-control"
                              onChange={(e) => {
                                updateStatus(booking.id, e.target.value);
                              }}
                              name="status"
                              id="status"
                            >
                              <option
                                value="Pending"
                                selected={booking.status === "Pending"}
                              >
                                Pending
                              </option>
                              <option
                                value="Confirmed"
                                selected={booking.status === "Confirmed"}
                              >
                                Confirmed
                              </option>
                              <option
                                value="Canceled"
                                selected={booking.status === "Canceled"}
                              >
                                Cancel
                              </option>
                            </select>
                          </form>
                        </td>

                        {/*<td className="d-flex justify-content-center">
                           <Link
                            to={`/admin/bookings/${booking.id}`}
                            className="btn-sm bg-success mr-1"
                          >
                            <i className="fa fa-eye"> </i>
                          </Link>
                          <Link
                            to={`/admin/bookings/edit/${booking.id}`}
                            className="btn-sm bg-teal mr-1"
                          >
                            <i className=" fas fa-edit"> </i>
                          </Link> 
                          <span
                            onClick={() => handleDelete(booking.id)}
                            className="btn-sm bg-danger mr-1"
                          >
                            <i className="fas fa-trash-alt"> </i>
                          </span>
                        </td>*/}
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default BookingIndex;
