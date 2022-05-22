import React from "react";
import { Link } from "react-router-dom";
import { useEffect, useState } from "react";
import useAxios from "../../hooks/useAxios";
import axios from "axios";
import { useParams } from "react-router-dom";
import UserContext from "../../UserContext";
import { useContext } from "react";

export const ViewVehicle = () => {
  const [vehicle, setVehicle] = useState({});
  const { id } = useParams();
  const [isLoading, setLoading] = useState(false);
  const [start_date, setStartDate] = useState("");
  const [end_date, setEndDate] = useState("");
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
        vehicle_id: vehicle.id,
        destination: requestVehicle.destination,
        start_date: start_date,
        end_date: end_date,
        total_amount: requestVehicle.total_amount,
        remarks: requestVehicle.remarks,
      })
      .then((res) => {
        setRequestVehicle(res.data);
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
  const handleInput = (e) => {
    setRequestVehicle({
      ...requestVehicle,
      [e.target.name]: e.target.value,
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
  useEffect(() => {
    fetchVehicle();
    fetchUser(); /* eslint-disable */
  }, []);
  return (
    <div>
      {/* <main className="px-4 py-6 sm:p-6 md:py-10 md:px-8">
        <div className="grid max-w-4xl grid-cols-1 mx-auto lg:max-w-5xl lg:gap-x-20 lg:grid-cols-2">
          <div className="relative flex flex-col-reverse col-start-1 row-start-1 p-3 rounded-lg bg-gradient-to-t from-black/75 via-black/0 sm:bg-none sm:row-start-2 sm:p-0 lg:row-start-1">
            <p className="mt-1 font-semibold text-white sm:text-slate-900 dark:sm:text-white">
              Seats:{vehicle.total_seats}
            </p>
            <p className="mt-1 font-semibold text-white sm:text-slate-900 dark:sm:text-white">
              Vendor Name:{vehicle.vendor_id}
            </p>
            <p className="mt-1 font-semibold text-white sm:text-slate-900 dark:sm:text-white">
              Type:{vehicle.type_id}
            </p>
            <p className="mt-1 font-semibold text-white sm:text-slate-900 dark:sm:text-white">
              Color: {vehicle.color}
            </p>
            <h1 className="mt-1 text-lg font-semibold text-white sm:text-slate-900 md:text-2xl dark:sm:text-white">
              {vehicle.name}
            </h1>

            <p className="text-sm font-medium leading-4 text-white sm:text-slate-500 dark:sm:text-slate-400">
              {vehicle.id}
            </p>
          </div>
          <div className="grid w-full col-start-1 col-end-3 row-start-1 gap-4 h-87 sm:mb-6 sm:grid-cols-4 lg:gap-6 lg:col-start-2 lg:row-end-6 lg:row-span-6 lg:mb-0">
            <img
              style={{ height: "290px" }}
              src={`http://localhost:8000/storage/${vehicle.image}`}
              className="object-cover w-full rounded-lg h-60 sm:h-52 sm:col-span-2 lg:col-span-full"
              loading="lazy"
            />
          </div>
          <dl className="mt-4 text-xs font-medium flex items-center row-start-2 sm:mt-1 sm:row-start-3 md:mt-2.5 lg:row-start-2">
            <dt className="sr-only">Reviews</dt>
            <dd className="flex items-center text-indigo-600 dark:text-indigo-400">
              <svg
                width={24}
                height={24}
                fill="none"
                aria-hidden="true"
                className="mr-1 stroke-current dark:stroke-indigo-500"
              >
                <path
                  d="m12 5 2 5h5l-4 4 2.103 5L12 16l-5.103 3L9 14l-4-4h5l2-5Z"
                  strokeWidth={2}
                  strokeLinecap="round"
                  strokeLinejoin="round"
                />
              </svg>
              <span>
                4.89 <span className="font-normal text-slate-400">(128)</span>
              </span>
            </dd>
            <dt className="sr-only">Location</dt>
            <dd className="flex items-center">
              <svg
                width={2}
                height={2}
                aria-hidden="true"
                fill="currentColor"
                className="mx-3 text-slate-300"
              >
                <circle cx={1} cy={1} r={1} />
              </svg>
              <svg
                width={24}
                height={24}
                fill="none"
                stroke="currentColor"
                strokeWidth={2}
                strokeLinecap="round"
                strokeLinejoin="round"
                className="mr-1 text-slate-400 dark:text-slate-500"
                aria-hidden="true"
              >
                <path d="M18 11.034C18 14.897 12 19 12 19s-6-4.103-6-7.966C6 7.655 8.819 5 12 5s6 2.655 6 6.034Z" />
                <path d="M14 11a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
              </svg>
              Collingwood, Ontario
            </dd>
          </dl>
          <div className="self-center col-start-1 row-start-3 mt-4 sm:mt-0 sm:col-start-2 sm:row-start-2 sm:row-span-2 lg:mt-6 lg:col-start-1 lg:row-start-3 lg:row-end-4">
            <div className="grid w-full col-start-1 col-end-3 row-start-1 gap-4 h-87 sm:mb-6 sm:grid-cols-4 lg:gap-6 lg:col-start-2 lg:row-end-6 lg:row-span-6 lg:mb-0">
              <img
                style={{ height: "290px" }}
                src={`http://localhost:8000/storage/${vehicle.image}`}
                className="object-cover w-full rounded-lg h-60 sm:h-52 sm:col-span-2 lg:col-span-full"
                loading="lazy"
              />
            </div>
            <p>Total Seat</p>
            <p>Total Seat</p>
            <p>Total Seat</p>
            <p>Total Seat</p>
            <p>Total Seat</p>
            <p>Total Seat</p>
            <div className="mt-5 mb-5"></div>
            <Link
              to="/book"
              className="px-3 py-2 text-sm font-medium leading-6 text-white bg-indigo-600 rounded-lg"
            >
              Check availability
            </Link>
          </div>
          <p className="col-start-1 mt-4 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
            {vehicle.description}
          </p>
        </div>
      </main>

      <section>Review</section>

      <section className="relative text-gray-600 body-font">
        <div className="container flex flex-wrap px-5 py-24 ">
          <div className="relative flex items-end justify-start overflow-hidden bg-gray-300 rounded-lg lg:w-2/3 md:w-1/2 sm:mr-10">
            <div className="grid w-full col-start-1 col-end-3 row-start-1 gap-4 h-87 sm:mb-6 sm:grid-cols-4 lg:gap-6 lg:col-start-2 lg:row-end-6 lg:row-span-6 lg:mb-0">
              <img
                src={`http://localhost:8000/storage/${vehicle.image}`}
                className="object-cover w-full rounded-lg h-60 sm:h-52 sm:col-span-2 lg:col-span-full"
              />
            </div>
        
          </div>
          <div className="flex flex-col w-full bg-white lg:w-1/3 md:w-1/2 md:ml-auto md:py-8 md:mt-0">
            <h2 className="mb-1 text-lg font-medium text-gray-900 title-font">
              Check Vehicle Availability
            </h2>
            <p className="mb-5 leading-relaxed text-gray-600"></p>
            <div className="relative mb-4">
              <label htmlFor="name" className="text-sm leading-7 text-gray-600">
                PickUp Date
              </label>
              <input
                type="datetime-local"
                name="start_date"
                className="w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-white border border-gray-300 rounded outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
              />
            </div>
            <div className="relative mb-4">
              <label
                htmlFor="email"
                className="text-sm leading-7 text-gray-600"
              >
                End Date
              </label>
              <input
                type="datetime-local"
                name="email"
                className="w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-white border border-gray-300 rounded outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
              />
            </div>

            <button className="px-6 py-2 text-lg text-white bg-indigo-500 border-0 rounded focus:outline-none hover:bg-indigo-600">
              CA
            </button>
          </div>
        </div>
      </section> */}

      <div className="gap-8 mx-auto md:mx-10 lg:flex my-14">
        <div className="w-full p-20 bg-white md:w-1/2">
          <div className="w-full ">
            <h1 className="mt-1 text-lg font-semibold text-white sm:text-slate-900 md:text-2xl dark:sm:text-white">
              {vehicle.name}
            </h1>

            <img
              src={`http://localhost:8000/storage/${vehicle.image}`}
              className="w-full"
            />
            <p className="text-lg text-gray-500">Description</p>
            <p className="col-start-1 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
              {vehicle.description}
            </p>
            <p className="col-start-1 mt-4 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
              {vehicle.type_id}
            </p>
            <p className="col-start-1 mt-4 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
              {vehicle.location_id}
            </p>
            <p className="col-start-1 mt-4 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
              {vehicle.vendor_id}
            </p>
            <p className="col-start-1 mt-4 text-sm leading-6 sm:col-span-2 lg:mt-6 lg:row-start-4 lg:col-span-1 dark:text-slate-400">
              {vehicle.color}
            </p>
          </div>
        </div>
        <div className="p-20 bg-white lg:mt-10 md:w-1/2 ">
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
                          name="total_amount"
                          value={requestVehicle.total_amount}
                          disabled
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
    </div>
  );
};
