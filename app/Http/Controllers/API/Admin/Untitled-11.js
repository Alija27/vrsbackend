import React from "react";
import { useEffect, useState } from "react";
import useAxios from "../../hooks/useAxios";
import { useParams } from "react-router-dom";
import UserContext from "../../UserContext";
import { useContext } from "react";
import { useNavigate } from "react-router-dom";
const VehicleDetails = () => {
  const navigate = useNavigate();
  const [rating, setRating] = useState(0);
  const [hover, setHover] = useState(null);
  const [vehicle, setVehicle] = useState({
    vendor: {},
    location: {},
    type: {},
  });
  const [eligibleForReview, setEligibleForReview] = useState(false);
  const { id } = useParams();
  const [isLoading, setLoading] = useState(false);
  const [start_date, setStartDate] = useState("");
  const [end_date, setEndDate] = useState("");
  const [types, setTypes] = useState({});
  const [locations, setLocations] = useState({});
  const [book_data, setBookData] = useState({});
  const [user, fetchUser] = useContext(UserContext);
  const [requestVehicle, setRequestVehicle] = useState({
    user_id: user.id,
    vehicle_id: vehicle.id,
    destination: "",
    start_date: "",
    end_date: "",
    total_amount: "",
    remarks: "",
  });
  function submitBook() {
    useAxios
      .post("/requestVehicle", {
        user_id: user.id,
        vehicle_id: vehicle.id,
        destination: requestVehicle.destination,
        start_date: start_date,
        end_date: end_date,
        total_amount: requestVehicle.total_amount,
        remarks: requestVehicle.remarks,
      })
      .then((res) => {
        setRequestVehicle(res.data);
        alert("Requested Sucessfully");
        navigate("/myBookings");
      })
      .catch((err) => {
        alert("Cannot send request");
      });
  }

  function submitForm() {
    setLoading(true);
    useAxios
      .post(`/checkvehicle/${id}`, { start_date, end_date })
      .then((res) => {
        setLoading(false);
        setBookData(res.data);
        setRequestVehicle({
          ...requestVehicle,
          total_amount: res.data.total_price,
        });
      })
      .catch((err) => {
        setLoading(false);
        alert("Cannot check vehicle status!");
      });
  }

  /* const rating = (pos, e) => {
    let star = e.target;
  };
 */
  const handleInput = (e) => {
    setRequestVehicle({
      ...requestVehicle,
      [e.target.name]: e.target.value,
    });
  };
  function Review() {
    useAxios.post(`/giveReview/`);
  }
  const getTypes = async () => {
    useAxios.get("http://localhost:8000/api/types").then((res) => {
      setTypes(res.data);
    });
  };

  const getLocations = async () => {
    useAxios.get("/locations").then((res) => {
      setLocations(res.data);
      console.log(res.data);
    });
  };

  const checkEligible = () => {
    useAxios.get(`/eligible/${id}`).then((res) => {
      if (res.data === 0) {
        setEligibleForReview(false);
      } else {
        setEligibleForReview(true);
      }
    });
  };

  const fetchVehicle = async () => {
    // await axios.get(`/vehicle/${id}`).then((res) => {
    //   setVehicle(res.data);
    // });
    await useAxios.get(`/vehicle/${id}`).then((res) => {
      setVehicle(res.data);
    });
    console.log(vehicle);
  };

  const saveReview = (e) => {
    e.preventDefault();
    useAxios
      .post(`/review/${id}`, {
        ...requestVehicle,
        stars: rating,
        vehicle_id: id,
        user_id: user.id,
      })
      .then((res) => {
        alert(res.data);
      })
      .catch((err) => {
        alert(err);
      });
  };

  useEffect(() => {
    fetchVehicle();
    fetchUser();
    getTypes();
    getLocations(); /* eslint-disable */
    checkEligible();
  }, []);
  return (
    <>
      <div className="relative overflow-hidden text-center h-96 p-50">
        <img
          alt="Jeremy S."
          className="object-fill"
          src={`http://localhost:8000/storage/${vehicle.image}`}
        />
      </div>
      <div className="gap-8 px-20 py-12 mx-auto md:mx-10 lg:flex ">
        <div className="w-full bg-white md:w-1/2">
          <div className="w-full ">
            <h1 className="text-3xl font-bold text-white sm:text-slate-900 dark:sm:text-white ">
              {vehicle.name} Bentley Continental GT 2012
            </h1>
            <p className="col-start-1 mt-4 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
              <p className="mt-1 text-lg text-gray-500 ">
                <i class="fa-solid fa-location-pin ml-1"></i>{" "}
                {vehicle.location.name}
              </p>
            </p>
            <p className="col-start-1 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
              <p className="mt-1 text-lg text-gray-500">Description</p>{" "}
              {vehicle.description}
            </p>
            <p className="col-start-1 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
              <p className="mt-1 text-lg text-gray-500">Terms</p>{" "}
              {vehicle.terms}
            </p>
            <p className="col-start-1 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
              <p className="mt-1 text-lg text-gray-500">Vehicle Conditon</p>{" "}
              {vehicle.condition}
            </p>

            <br />
          </div>
        </div>
        <div className="bg-white md:w-1/2">
          <p className="col-start-1 mt-4 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
            <p className="mt-1 text-lg text-gray-500">Rental Price</p>
            <h2 className="mt-1 text-lg text-black text-bold">
              Rs. {vehicle.rental_price}/day
            </h2>
          </p>
          <div className="p-5 border border-indigo-600 border-6">
            <form
              onSubmit={(e) => {
                e.preventDefault();
                submitForm();
              }}
            >
              <div className="relative mb-4">
                <label
                  htmlFor="name"
                  className="text-sm leading-7 text-gray-600"
                >
                  PickUp Date
                </label>
                <input
                  type="datetime-local"
                  name="start_date"
                  value={start_date}
                  onChange={(e) => {
                    setStartDate(e.target.value);
                  }}
                  className="w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-white border border-gray-300 rounded outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                />
              </div>
              <div className="relative mb-4">
                <label
                  htmlFor="end_date"
                  className="text-sm leading-7 text-gray-600"
                >
                  End Date
                </label>
                <input
                  type="datetime-local"
                  name="end_date"
                  value={end_date}
                  onChange={(e) => {
                    setEndDate(e.target.value);
                  }}
                  className="w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-white border border-gray-300 rounded outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                />
              </div>

              <button className="w-full px-6 py-2 text-lg text-white bg-indigo-500 border-0 rounded focus:outline-none hover:bg-indigo-600">
                Check Availability
              </button>
            </form>
            {isLoading && (
              <div>
                <p>Checking availability...</p>
              </div>
            )}

            {!isLoading && book_data.status && (
              <div>
                <p>{book_data.status}</p>
                {book_data.is_available && (
                  <div>
                    <form
                      onSubmit={(e) => {
                        e.preventDefault();
                        submitBook();
                      }}
                    >
                      <div className="relative mb-4">
                        <input
                          type="hidden"
                          name="start_date"
                          value={start_date}
                          className="w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-white border border-gray-300 rounded outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        />
                      </div>
                      <div className="relative mb-4">
                        <input
                          type="hidden"
                          name="end_date"
                          value={end_date}
                          className="w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-white border border-gray-300 rounded outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        />
                      </div>
                      <div className="relative mb-4">
                        Destination
                        <input
                          name="destination"
                          value={requestVehicle.destination}
                          onChange={handleInput}
                          className="w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-white border border-gray-300 rounded outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        />
                      </div>
                      <div className="relative mb-4">
                        Total Amount
                        <input
                          disabled
                          name="total_amount"
                          value={requestVehicle.total_amount}
                          className="w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-white border border-gray-300 rounded outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        />
                      </div>
                      <div className="relative mb-4">
                        Remarks
                        <input
                          name="remarks"
                          value={requestVehicle.remarks}
                          onChange={handleInput}
                          className="w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-white border border-gray-300 rounded outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        />
                      </div>

                      <button className="w-full px-6 py-2 text-lg text-white bg-indigo-500 border-0 rounded focus:outline-none hover:bg-indio-600">
                        Request Vehicle
                      </button>
                    </form>
                  </div>
                )}
              </div>
            )}
          </div>
        </div>
      </div>

      <div className="flex max-w-md overflow-hidden bg-white rounded-lg shadow-lg mx-9 dark:bg-gray-800">
        <img
          src={`http://localhost:8000/storage/${vehicle.vendor.image}`}
          className="w-1/3 bg-cover"
        />
        <div className="w-2/3 p-4 md:p-4">
          <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Hosted By:
          </p>
          <h1 className="text-2xl font-bold text-gray-800 dark:text-white">
            {vehicle.vendor.name}
          </h1>
          <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {vehicle.vendor.phone}
          </p>

          <div className="flex justify-between mt-3 item-center">
            <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
              {vehicle.vendor.address}
            </p>
            <h1 className="text-lg font-bold text-gray-700 dark:text-gray-200 md:text-xl"></h1>
          </div>
        </div>
      </div>
      <div>
          {/* <div className="reviewList-review">
        <div className="css-q4kmj6-MediaObjectWrapper">
          <a
            rel="nofollow"
            className="css-rdoxx0-MediaObjectItem"
            href="/gb/en/drivers/8074421"
          >
            <img
              alt="Jeremy S."
              className="css-5xc2cd"
              src="https://images.turo.com/media/driver/fqy8PZkTQq6lvwKhaVlExg.96x96.jpg"
            />
          </a>
          <div className="css-1x157x8-ReviewBody-MediaObjectBody">
            <div className="css-1qr3nc0-StarRating-Container">
              <div
                aria-label="Rating: 5 out of 5 stars"
                role="img"
                className="css-1addy8h"
              >
                <div aria-hidden="true" className="css-10pswck">
                  <svg
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    className="css-1moeh8l"
                    role="img"
                    version="1.1"
                  >
                    <path
                      fillRule="evenodd"
                      clipRule="evenodd"
                      d="m15.153 8.498 5.906.41c.904.064 1.274 1.197.582 1.783l-4.52 3.835 1.377 5.72c.212.88-.746 1.576-1.514 1.1L12 18.25l-4.983 3.095c-.77.477-1.727-.22-1.515-1.098l1.379-5.72-4.516-3.829c-.696-.582-.334-1.716.568-1.787l5.907-.413 2.226-5.373c.345-.833 1.522-.833 1.866 0l2.22 5.373Z"
                      fill="#121214"
                    />
                  </svg>
                </div>
                <div aria-hidden="true" className="css-10pswck">
                  <svg
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    className="css-1moeh8l"
                    role="img"
                    version="1.1"
                  >
                    <path
                      fillRule="evenodd"
                      clipRule="evenodd"
                      d="m15.153 8.498 5.906.41c.904.064 1.274 1.197.582 1.783l-4.52 3.835 1.377 5.72c.212.88-.746 1.576-1.514 1.1L12 18.25l-4.983 3.095c-.77.477-1.727-.22-1.515-1.098l1.379-5.72-4.516-3.829c-.696-.582-.334-1.716.568-1.787l5.907-.413 2.226-5.373c.345-.833 1.522-.833 1.866 0l2.22 5.373Z"
                      fill="#121214"
                    />
                  </svg>
                </div>
                <div aria-hidden="true" className="css-10pswck">
                  <svg
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    className="css-1moeh8l"
                    role="img"
                    version="1.1"
                  >
                    <path
                      fillRule="evenodd"
                      clipRule="evenodd"
                      d="m15.153 8.498 5.906.41c.904.064 1.274 1.197.582 1.783l-4.52 3.835 1.377 5.72c.212.88-.746 1.576-1.514 1.1L12 18.25l-4.983 3.095c-.77.477-1.727-.22-1.515-1.098l1.379-5.72-4.516-3.829c-.696-.582-.334-1.716.568-1.787l5.907-.413 2.226-5.373c.345-.833 1.522-.833 1.866 0l2.22 5.373Z"
                      fill="#121214"
                    />
                  </svg>
                </div>
                <div aria-hidden="true" className="css-10pswck">
                  <svg
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    className="css-1moeh8l"
                    role="img"
                    version="1.1"
                  >
                    <path
                      fillRule="evenodd"
                      clipRule="evenodd"
                      d="m15.153 8.498 5.906.41c.904.064 1.274 1.197.582 1.783l-4.52 3.835 1.377 5.72c.212.88-.746 1.576-1.514 1.1L12 18.25l-4.983 3.095c-.77.477-1.727-.22-1.515-1.098l1.379-5.72-4.516-3.829c-.696-.582-.334-1.716.568-1.787l5.907-.413 2.226-5.373c.345-.833 1.522-.833 1.866 0l2.22 5.373Z"
                      fill="#121214"
                    />
                  </svg>
                </div>
                <div aria-hidden="true" className="css-10pswck">
                  <svg
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    className="css-1moeh8l"
                    role="img"
                    version="1.1"
                  >
                    <path
                      fillRule="evenodd"
                      clipRule="evenodd"
                      d="m15.153 8.498 5.906.41c.904.064 1.274 1.197.582 1.783l-4.52 3.835 1.377 5.72c.212.88-.746 1.576-1.514 1.1L12 18.25l-4.983 3.095c-.77.477-1.727-.22-1.515-1.098l1.379-5.72-4.516-3.829c-.696-.582-.334-1.716.568-1.787l5.907-.413 2.226-5.373c.345-.833 1.522-.833 1.866 0l2.22 5.373Z"
                      fill="#121214"
                    />
                  </svg>
                </div>
              </div>
            </div>
            <p className="css-yf4h6g-StyledText">
              <span>Jeremy</span>
              <span className="css-1bjw4qe-StyledText-ReviewDate">
                Nov 7, 2021
              </span>
            </p>
            <p className="css-mrzgsa-StyledText">
              Awesome car. Totally worth spending a little extra. Easy drop off
              and pick up!
            </p>
          </div>
        </div>
      </div> */}
      </div>
    </>
  );
};
export default VehicleDetails;
